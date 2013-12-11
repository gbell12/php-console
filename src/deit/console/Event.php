<?php

namespace deit\console;
use deit\console\command\CommandInterface;

/**
 * Console event
 * @author James Newell <james@digitaledgeit.com.au>
 */
class Event extends \deit\event\Event{

	/**
	 * The application
	 * @var     Application
	 */
	private $application;

	/**
	 * The command
	 * @var     CommandInterface
	 */
	private $command;

	/**
	 * The console
	 * @var     ConsoleInterface
	 */
	private $console;

	/**
	 * The exit code
	 * @var     int
	 */
	private $exitCode;

	/**
	 * The exception
	 * @var     \Exception
	 */
	private $exception;

	/**
	 * Gets the application
	 * @return  Application
	 */
	public function getApplication() {
		return $this->application;
	}

	/**
	 * Sets the application
	 * @param   Application $application
	 * @return  $this
	 */
	public function setApplication(Application $application) {
		$this->application = $application;
		return $this;
	}

	/**
	 * Sets the command
	 * @return  CommandInterface
	 */
	public function getCommand() {
		return $this->command;
	}

	/**
	 * Gets the command
	 * @param   CommandInterface $command
	 * @return  $this
	 */
	public function setCommand(CommandInterface $command) {
		$this->command = $command;
		return $this;
	}

	/**
	 * Gets the console
	 * @return  ConsoleInterface
	 */
	public function getConsole() {
		return $this->console;
	}

	/**
	 * Sets the console
	 * @param   ConsoleInterface $console
	 * @return  $this
	 */
	public function setConsole(ConsoleInterface $console) {
		$this->console = $console;
		return $this;
	}

	/**
	 * Gets the exit code
	 * @return  int
	 */
	public function getExitCode() {
		return $this->exitCode;
	}

	/**
	 * Sets the exit code
	 * @param   int     $code
	 * @return  $this
	 */
	public function setExitCode($code) {
		$this->exitCode = $code;
		return $this;
	}

	/**
	 * Gets the exception
	 * @return  \Exception
	 */
	public function getException() {
		return $this->exception;
	}

	/**
	 * Sets the exception
	 * @param   \Exception $exception
	 * @return  $this
	 */
	public function setException(\Exception $exception) {
		$this->exception = $exception;
		return $this;
	}

}
 