<?php

namespace deit\console;
use deit\console\definition\Definition;

/**
 * Console command
 * @author James Newell <james@digitaledgeit.com.au>
 */
abstract class Command {

	/**
	 * The definition
	 * @var     Definition
	 */
	private $definition;

	/**
	 * The application
	 * @var     Application
	 */
	private $application;

	/**
	 * Constructs the command
	 */
	public function __construct() {
		$this->definition = new Definition();
		$this->configure();
	}

	/**
	 * Gets the console definition
	 * @return  Definition
	 */
	public function getDefinition() {
		return $this->definition;
	}

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
	 * @return $this
	 */
	public function setApplication(Application $application) {
		$this->application = $application;
		return $this;
	}

	/**
	 * Configures the command
	 */
	public function configure() {
	}

	/**
	 * The command main method
	 * @param   ConsoleInterface    $console    The command console
	 * @return  int                             The command exit code
	 */
	abstract public function main(ConsoleInterface $console);

} 