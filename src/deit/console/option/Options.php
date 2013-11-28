<?php

namespace deit\console\option;

/**
 * Console options
 * @author James Newell <james@digitaledgeit.com.au>
 */
class Options {

	/**
	 * The console options
	 * @var     mixed[string]
	 */
	private $options    = [];

	/**
	 * The console arguments
	 * @var     mixed[string]
	 */
	private $arguments  = [];

	/**
	 * Gets all option values
	 * @return  mixed[string]
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * Sets all option values
	 * @param   mixed[string] $options
	 * @return  $this
	 */
	public function setOptions(array $options) {
		$this->options = $options;
		return $this;
	}

	/**
	 * Gets an option value
	 * @param   string  $name    The option name
	 * @return  bool
	 */
	public function hasOption($name) {
		return array_key_exists($name, $this->options);
	}

	/**
	 * Gets an option value
	 * @param   string  $name    The option name
	 * @param   string  $value   The default option value
	 * @return  string
	 */
	public function getOption($name, $value = null) {
		if (array_key_exists($name, $this->options)) {
			return $this->options[$name];
		} else {
			return $value;
		}
	}

	/**
	 * Sets an option value
	 * @param   string  $name    The option name
	 * @param   string  $value   The default option value
	 * @return  $this
	 */
	public function setOption($name, $value = null) {
		$this->options[$name] = $value;
		return $this;
	}

} 