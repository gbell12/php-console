<?php

namespace deit\console\option;

/**
 * Console option
 * @author James Newell <james@digitaledgeit.com.au>
 */
class Option {

	const VALUE_NONE        = 0;
	const VALUE_REQUIRED    = 1;
	const VALUE_OPTIONAL    = 2;
	const VALUE_ARRAY       = 4;

	const OPTION_REQUIRED   = 8;

	/**
	 * The name
	 * @var     string
	 */
	private $name;

	/**
	 * The shortcut
	 * @var     string
	 */
	private $shortcut;

	/**
	 * The value mode
	 * @var     int
	 */
	private $mode;

	/**
	 * The default value
	 * @var     string
	 */
	private $default;

	/**
	 * Constructs the option
	 * @param   string  $name       The option name
	 * @param   string  $shortcut   The option shortcut
	 * @param   int     $mode       The option mode
	 * @param   string  $default    The default value
	 * @throws
	 */
	public function __construct($name, $shortcut = null, $mode = self::VALUE_NONE, $default = null) {

		//check the name is alphanumeric
		if (preg_match('#^[a-zA-Z0-9\-]+$#', $name) < 1) {
			throw new \InvalidArgumentException("Name \"$name\" must consist of alphanumeric characters.");
		}

		//check the shortcut is alphanumeric and one character long
		if (!is_null($shortcut) && preg_match('#^[a-zA-Z0-9]$#', $shortcut) < 1) {
			throw new \InvalidArgumentException("Shortcut \"$shortcut\" must consist of a single alphanumeric character.");
		}

		//check the mode is valid
		if (!is_int($mode) || $mode < 0 || $mode > 15) {
			throw new \InvalidArgumentException("Invalid mode.");
		} else {

			//check the mode is not both required and optional at the same time
			if (($mode & self::VALUE_REQUIRED) && ($mode & self::VALUE_OPTIONAL)) {
				throw new \InvalidArgumentException("Mode cannot be both required and optional at the same time.");
			}

			//check that if the mode is array that a value is required or optional
			if ($mode == self::VALUE_ARRAY) {
				throw new \InvalidArgumentException("Mode must allow a value.");
			}

		}

		$this->name     = $name;
		$this->shortcut = $shortcut;
		$this->mode     = $mode;
		$this->default  = $default;

	}

	/**
	 * Gets the name
	 * @return  string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Gets the shortcut
	 * @return  string
	 */
	public function getShortcut() {
		return $this->shortcut;
	}

	/**
	 * Gets the mode
	 * @return  int
	 */
	public function getMode() {
		return $this->mode;
	}

	/**
	 * Gets whether the option is required
	 * @return  bool
	 */
	public function isRequired() {
		return self::OPTION_REQUIRED === ($this->mode & self::OPTION_REQUIRED);
	}

	/**
	 * Gets whether a value is required
	 * @return  bool
	 */
	public function isValueRequired() {
		return self::VALUE_REQUIRED === ($this->mode & self::VALUE_REQUIRED);
	}

	/**
	 * Gets whether a value is optional
	 * @return  bool
	 */
	public function isValueOptional() {
		return self::VALUE_OPTIONAL === ($this->mode & self::VALUE_OPTIONAL);
	}

	/**
	 * Gets whether the value is an array
	 * @return  bool
	 */
	public function isValueArray() {
		return self::VALUE_ARRAY === ($this->mode & self::VALUE_ARRAY);
	}

	/**
	 * Gets whether a value is permitted
	 * @return  bool
	 */
	public function isValueAllowed() {
		return  $this->isValueRequired() || $this->isValueOptional();
	}

	/**
	 * Gets the default value
	 * @return  string
	 */
	public function getDefault() {
		return $this->default;
	}

} 