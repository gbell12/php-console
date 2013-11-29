<?php

namespace deit\console;

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
	private $commands = [];

	/**
	 * Constructs the application
	 * @param   ConsoleInterface    $console    The command console
	 */
	public function __construct(ConsoleInterface $console = null) {
		$this->console = $console;
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

		//get the command to run
		if (!($command = $this->getCommand($name))) {
			throw new \InvalidArgumentException("No commands have been added.");
		}

		//validate the command definition TODO: move this to an event listener on the command
		try {
			$command->getDefinition()->validate($console);
		} catch (\Exception $exception) {
			$console->getErrorStream()->write("Error: {$exception->getMessage()}\n");
			return -1;
		}

		//run the command
		try {
			$exitCode = $command->execute($console);
		} catch (\Exception $exception) {
			$console->getErrorStream()->write("Error: {$exception->getTraceAsString()}\n");
			return -1;
		}

		return $exitCode;
	}

} 