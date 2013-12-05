<?php

namespace deit\console\definition;

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
	 * The short name
	 * @var     string
	 */
	private $shortName;

	/**
	 * The long name
	 * @var     string
	 */
	private $longName;

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
	 * The filter
	 * @var 	callable
	 */
	private $filter;
		
	/**
	 * The validator
	 * @var 	callable
	 */
	private $validator;

	/**
	 * Constructs the option
	 * @param   string|string[]     $name       The option name(s)
	 * @param   int                 $mode       The option mode
	 * @param   string              $default    The option default
	 * @param   callable            $filter    	The option default
	 * @param   callable            $validator  The option default
	 * @throws
	 */
	public function __construct($name, $mode = self::VALUE_NONE, $default = null, callable $filter = null, callable $validator = null) {

		//sets the name
				
		if (is_array($name)) {
			$names = $name;
		} else {
			$names = explode('|', $name, 2);
		}
		
		foreach ($names as $name) {
			if (strlen($name) > 1) {
				$this->setLongName($name);
			} else {
				$this->setShortName($name);
			}
		}

		//sets the mode and default
		$this
			->setMode($mode)
			->setDefault($default)
		;
		
		if (!is_null($filter)) {
			$this->setFilter($filter);
		}
		
		if (!is_null($validator)) {
			$this->setValidator($validator);
		}

	}
	
	/**
	 * Gets the full name
	 * @return  string
	 */
	public function getName() {
		if (empty($this->longName)) {
			return $this->shortName;
		} else {
			return $this->longName;
		}
	}

	/**
	 * Gets all the names
	 * @return 	string[]
	 */
	public function getNames() {
		return array(
			$this->getShortName(),
			$this->getLongName(),
		);
	}
	
	/**
	 * Gets the short name
	 * @return  string
	 */
	public function getShortName() {
		return $this->shortName;
	}

	/**
	 * Sets the short name
	 * @param   string  $name       The long name
	 * @return  $this
	 * @throws
	 */
	public function setShortName($name) {

		//check the short name is alphanumeric and is only one character long
		if (!is_null($name) && preg_match('#^[a-zA-Z0-9]$#', $name) < 1) {
			throw new \InvalidArgumentException("Short name \"$name\" must consist of a single alphanumeric character.");
		}
		$this->shortName = (string) $name;
		return $this;
	}

	/**
	 * Gets the long name
	 * @return  string
	 */
	public function getLongName() {
		return $this->longName;
	}

	/**
	 * Sets the long name
	 * @param   string  $name       The short name
	 * @return  $this
	 * @throws  \InvalidArgumentException
	 */
	public function setLongName($name) {

		//check the long name is alphanumeric and is more than one character long
		if (preg_match('#^[a-zA-Z0-9\-]{2,}#', $name) < 1) {
			throw new \InvalidArgumentException("Long name \"$name\" must consist of more than one alphanumerical character.");
		}

		$this->longName = $name;
		return $this;
	}

	/**
	 * Gets the mode
	 * @return  int
	 */
	public function getMode() {
		return $this->mode;
	}

	/**
	 * Sets the mode
	 * @param   int     $mode       The option mode
	 * @return  $this
	 * @throws  \InvalidArgumentException
	 */
	public function setMode($mode) {

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

		$this->mode = $mode;
		return $this;
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

	/**
	 * Sets the default value
	 * @param   string $value
	 * @return  $this
	 */
	public function setDefault($value) {
		$this->default = $value;
		return $this;
	}

	/**
	 * Gets the filter
	 * @return  callable
	 */
	public function getFilter() {
		return $this->filter;
	}

	/**
	 * Sets the filter
	 * @param   callable $filter
	 * @return  $this
	 */
	public function setFilter(callable $filter) {
		$this->filter = $filter;
		return $this;
	}
	
	/**
	 * Gets the validator
	 * @return  callable
	 */
	public function getValidator() {
		return $this->validator;
	}

	/**
	 * Sets the default value
	 * @param   callable $validator
	 * @return  $this
	 */
	public function setValidator(callable $validator) {
		$this->validator = $validator;
		return $this;
	}
	
} 