<?php

namespace deit\console;
use \deit\console\command\CommandInterface;
use deit\console\parser\ArgvParser;
use \deit\event\EventManager;
use \deit\event\EventListenerInterface;
use \deit\stream\AnsiOutputStream;
use deit\stream\PhpInputStream;
use deit\stream\PhpOutputStream;

/**
 * Console application
 * @author James Newell <james@digitaledgeit.com.au>
 */
class Application {

	const EVENT_DISPATCH    = 'dispatch';
	const EVENT_INTERRUPT   = 'interrupt';
	const EVENT_EXCEPTION   = 'exception';

	/**
	 * The event manager
	 * @var     EventManager
	 */
	private $em;

	/**
	 * The commands
	 * @var     Command[]
	 */
	private $commands;

	/**
	 * Constructs the application
	 */
	public function __construct() {
		$this->em       = new EventManager();
		$this->commands = array();


		//handle dispatch by running the command
		$this->em->attach(self::EVENT_DISPATCH, function($event) {

			//get the command
			$command = $event->getCommand();
			$command->setEvent($event);

			//check for a command
			if (is_null($command)) {
				throw new \InvalidArgumentException('Command required');
			}

			//attach the command to the event
			if ($command instanceof EventListenerInterface) {
				$command->attach($this->getEventManager());
			}

			//run the command
			$exitCode = $command->dispatch($event);

			//detach the command from the event
			if ($command instanceof EventListenerInterface) {
				$command->detach($this->getEventManager());
			}

			//set the exit code
			$event->setExitCode($exitCode);

		});

		//handle exceptions by printing the messages
		$this->em->attach(self::EVENT_EXCEPTION, function($event) {
			$this->printError($event, $event->getException()->getMessage());
		});

	}

	/**
	 * Gets the path
	 * @return  string
	 */
	public function getPath() {
		return realpath(dirname($_SERVER['SCRIPT_NAME']));
	}

	/**
	 * Gets the event manager
	 * @return  EventManager
	 */
	public function getEventManager() {
		return $this->em;
	}

	/**
	 * Gets the commands
	 * @return  CommandInterface[]
	 */
	public function getCommands() {
		return $this->commands;
	}

	/**
	 * Gets whether a command with the specified name has been added
	 * @param   string  $name       The command name
	 * @return  bool
	 */
	public function hasCommand($name = null) {
		if (empty($name)) {
			return count($this->commands) > 1;
		} else {
			return isset($this->commands[$name]);
		}
	}

	/**
	 * Gets the command with the specified name has been added
	 * @param   string  $name       The command name
	 * @return  CommandInterface|null
	 * @throws
	 */
	public function getCommand($name = null) {

		if (empty($name)) {

			//check there is only one command
			if (count($this->commands) > 1) {
				throw new \InvalidArgumentException("More than one command has been added. A command name is required.");
			}

			//get the first command
			$command = reset($this->commands);

			return $command ? $command : null;

		} else {

			//get the named command
			if (isset($this->commands[$name])) {
				return $this->commands[$name];
			} else {
				return null;
			}

		}

	}

	/**
	 * Adds a command
	 * @param   CommandInterface $command
	 * @return  $this
	 * @throws
	 */
	public function addCommand(CommandInterface $command) {

		//validate the name
		$name = $command->getDefinition()->getName();
		if (empty($name)) {
			throw new \InvalidArgumentException("Command names cannot be empty.");
		}

		//add the command
		$this->commands[$name] = $command;

		return $this;
	}

	/**
	 * Runs the specified command
	 * @param   Event          $event       The event
	 * @return  int                         The command exit code
	 * @throws
	 */
	public function run(Event $event = null) {

		if ($event == null) {

			//create the event
			$event = new Event();
			$event
				->setName(self::EVENT_DISPATCH)
				->setApplication($this)
				->setInputStream(new PhpInputStream(STDIN, false))
				->setOutputStream(new PhpOutputStream(STDOUT, false))
				->setErrorStream(new PhpOutputStream(STDERR, false))
				->setOptions(array())
				->setArguments(array())
			;


			//parse the command line arguments
			$parser = new ArgvParser();
			$parser->parse($event);

		}

		//start listening for signals
		$this->setUpSignalHandler();

		try {

			//get the command name
			if (count($this->commands) > 1) {

				//check for the command argument
				if (!$event->hasArgument(0)) {
					throw new \InvalidArgumentException('Command argument required');
				}

				//get the name argument
				$name = $event->getArgument(0);

				//remove the command argument from the arguments
				$argv = $event->getArguments();
				array_shift($argv);
				$event->setArguments($argv);

			} else {
				$name = null;
			}

			//get the command to run
			if (!($command = $this->getCommand($name))) {
				throw new \InvalidArgumentException("Command \"{$name}\" not found.");
			}

			//set the command
			$event->setCommand($command);

			//trigger the event
			$this->em->trigger($event);

		} catch (\Exception $exception) {

			//stop listening for signals
			$this->tearDownSignalHandler();

			//trigger the exception event
			try {
				$eevent = clone $event;
				$eevent
					->setName(self::EVENT_EXCEPTION)
					->setException($exception)
				;
				$this->em->trigger($eevent);
			} catch (\Exception $exception) {
				$this->printError($event, "An unhandled exception occurred: {$exception->getMessage()}");
			}

			return -1;
		}

		//stop listening for signals
		$this->tearDownSignalHandler();

		return $event->getExitCode();
	}

	/**
	 * Writes an error to stderr
	 * @param   Event           $event
	 * @param   string          $msg
	 */
	private function printError(Event $event, $msg) {
		$ansi = new AnsiOutputStream($event->getErrorStream());
		$ansi->fg(AnsiOutputStream::COLOUR_RED);
		$ansi->write("ERROR: ".$msg."\n");
	}

	/**
	 * Sets up the signal handler
	 */
	private function setUpSignalHandler() {
		if (function_exists('pcntl_signal')) {
			pcntl_signal(SIGINT, array($this, 'signalHandler'));
			pcntl_signal(SIGTERM, array($this, 'signalHandler'));
		}
	}

	/**
	 * Tears down the signal handler
	 */
	private function tearDownSignalHandler() {
		if (function_exists('pcntl_signal')) {
			pcntl_signal(SIGINT, SIG_DFL);
			pcntl_signal(SIGTERM, SIG_DFL);
		}
	}

	/**
	 * Handles the signal
	 * @param   int     $signal
	 * @return  void
	 */
	public function signalHandler($signal) {
		if ($signal == SIGINT || $signal == SIGTERM) {

			//create the event
			$event = new Event(self::EVENT_INTERRUPT);
			$event  //TODO: set command and console objects
				->setApplication($this)
				->setExitCode($signal)
			;

			//trigger the event and let cleanup happen
			$this->getEventManager()->trigger($event);

			//check if we're allowed to exit

			//exit
			exit($event->getExitCode());

		}
	}

} 