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
	 * Configures the command
	 */
	public function configure() {
	}

	/**
	 * Executes the command
	 * @param   ConsoleInterface    $console    The command console
	 * @return  int                             The command exit code
	 */
	abstract public function execute(ConsoleInterface $console);

} 