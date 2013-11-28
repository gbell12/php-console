<?php

namespace deit\console\option;

/**
 * Console definition
 * @author James Newell <james@digitaledgeit.com.au>
 */
class Definition {

	/**
	 * The command name
	 * @var     string
	 */
	private $name;

	/**
	 * The command description
	 * @var     string
	 */
	private $description;

	/**
	 * The option definitions
	 * @var     Option[string]
	 */
	private $options = [];

	/**
	 * The argument definitions
	 * @var     Argument[string]
	 */
	private $arguments = [];

	/**
	 * The command version
	 * @var     string
	 */
	private $version;

	/**
	 * Gets the command name
	 * @return  string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the command name
	 * @param   string  $name
	 * @return  $this
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Gets the command description
	 * @return  string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the command description
	 * @param   string  $description
	 * @return  $this
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * Gets the options
	 * @return  Option[]
	 */
	public function getOptions() {
		return array_values($this->options);
	}

	/**
	 * Gets whether the option is defined
	 * @param   string $name
	 * @return  bool
	 */
	public function hasOption($name) {
		return isset($this->options[$name]);
	}

	/**
	 * Gets an option
	 * @param   string  $name
	 * @return  Option|null
	 */
	public function getOption($name) {
		if (isset($this->options[$name])) {
			return $this->options[$name];
		} else {
			return null;
		}
	}

	/**
	 * Gets an option from the shortcut
	 * @param   string  $shortcut
	 * @return  Option|null
	 */
	public function getOptionByShortcut($shortcut) {
		foreach ($this->options as $option) {
			if ($option->getShortcut() == $shortcut) {
				return $option;
			}
		}
		return null;
	}

	/**
	 * Adds an option
	 * @param   Option $option
	 * @return  $this
	 */
	public function addOption(Option $option) {
		$this->options[$option->getName()] = $option;
		return $this;
	}

} 