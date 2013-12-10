<?php

namespace deit\console;
use \deit\stream\AnsiOutputStream;

/**
 * Console application
 * @author James Newell <james@digitaledgeit.com.au>
 */
class Application {

	/**
	 * The command console
	 * @var     ConsoleInterface
	 */
	private $console;

	/**
	 * The commands
	 * @var     Command[]
	 */
	private $commands = array();

	/**
	 * Constructs the application
	 * @param   ConsoleInterface    $console    The command console
	 */
	public function __construct(ConsoleInterface $console = null) {
		$this->console = $console;
	}

	/**
	 * Gets the path
	 * @return  string
	 */
	public function getPath() {
		return realpath(dirname($_SERVER['SCRIPT_NAME']));
	}

	/**
	 * Gets the console
	 * @return  ConsoleInterface
	 */
	public function getConsole() {
		if (is_null($this->console)) {
			$this->console = new StandardConsole();
		}
		return $this->console;
	}
	
	/**
	 * Gets the commands
	 * @return  Command[]
	 */
	public function getCommands() {
		return $this->commands;
	}

	/**
	 * Gets whether a command with the specified name has been added
	 * @param   string  $name       The command name
	 * @return  bool
	 */
	public function hasCommand($name) {
		return isset($this->commands[$name]);
	}

	/**
	 * Gets the command with the specified name has been added
	 * @param   string  $name       The command name
	 * @return  Command|null
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
	 * @param   Command $command
	 * @return  $this
	 * @throws
	 */
	public function addCommand(Command $command) {

		//validate the name
		$name = $command->getDefinition()->getName();
		if (empty($name)) {
			throw new \InvalidArgumentException("Command names cannot be empty.");
		}

		//add the command
		$command->setApplication($this);
		$this->commands[$name] = $command;

		return $this;
	}

	/**
	 * Runs the specified command
	 * @param   string          $name       The command name
	 * @return  int                         The command exit code
	 * @throws
	 */
	public function run($name = null) {

		//get the console
		$console = $this->getConsole();
		
		//check for a name argument
		if (is_null($name)) {
			$name = $console->getArgument(1);
		}
		
		//get the command to run
		if (!($command = $this->getCommand($name))) {
			throw new \InvalidArgumentException("Command \"{$name}\" not found.");
		}

		//start listening for signals
		$this->setUpSignalHandler();

		try {

			//validate the command definition
			$command->getDefinition()->validate($console); //TODO: move this to an event listener on the command

		} catch (\Exception $exception) {

			//report the exception
			$this->writeError($console, "Error: {$exception->getMessage()}");

			//stop listening for signals
			$this->tearDownSignalHandler();

			return -1;
		}

		try {

			//run the command
			$exitCode = $command->main($console);

		} catch (\Exception $exception) {

			//report the exception
			$this->writeError($console, "Error: {$exception->getMessage()}");

			//stop listening for signals
			$this->tearDownSignalHandler();

			return -1;
		}

		//stop listening for signals
		$this->tearDownSignalHandler();

		return $exitCode;
	}

	/**
	 * Writes an error to stderr
	 * @param ConsoleInterface $console
	 * @param                  $msg
	 */
	private function writeError(ConsoleInterface $console, $msg) {
		$ansi = new AnsiOutputStream($console->getErrorStream());
		$ansi->fg(AnsiOutputStream::COLOUR_RED);
		$ansi->write($msg);
		$ansi->write("\n");
	}

	/**
	 * Sets up the signal handler
	 */
	private function setUpSignalHandler() {
		if (function_exists('pcntl_signal')) {
			declare(ticks = 100);
			pcntl_signal(SIGTERM, array($this, 'signalHandler'));
		}
	}

	/**
	 * Tears down the signal handler
	 */
	private function tearDownSignalHandler() {
		if (function_exists('pcntl_signal')) {
			pcntl_signal(SIGTERM, SIG_DFL);
		}
	}

	/**
	 * Handles the signal
	 * @param   int     $signal
	 * @return  void
	 */
	private function signalHandler($signal) {
		$this->getEventManager()->trigger('interrupt');
	}

} 