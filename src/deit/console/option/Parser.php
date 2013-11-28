<?php

namespace deit\console\option;

/**
 * Console parser
 *  - Supports a limited subset of GNU options -a -b -c -abc -o=v --option --option=value -- -notoptions --notanoption
 *  - All options are optional, option values my be required as per option mode
 * @author James Newell <james@digitaledgeit.com.au>
 */
class Parser {

	/**
	 * The console options
	 * @var     Options
	 */
	private $options;

	/**
	 * The console definition
	 * @var     Definition
	 */
	private $definition;

	/**
	 * Constructs the console input
	 * @param   Definition $definition
	 */
	public function __construct(Definition $definition) {
		$this->definition   = $definition;
	}

	/**
	 * Parse the console input
	 * @param   string[] $argv
	 * @return  Options
	 * @throws
	 */
	public function parse(array $argv) {
		$this->options = new Options();

		//parse options and arguments
		while ($arg = current($argv)) {

			if ($arg == '--') {
				break; //only parse arguments now
			} else if (strpos($arg, '--') === 0) {
				$this->parseLongOption($arg);
			} else if (strpos($arg, '-') === 0) {
				$this->parseShortOption($arg);
			} else {
				//argument
			}

			next($argv);
		}

		//check all the required options have values
		foreach ($this->definition->getOptions() as $option) {
			if (!$this->options->hasOption($option->getName())) {
				throw new ParserException("Option \"{$option->getName()}\" is required.");
			}
		}

		return $this->options;
	}

	/**
	 * Parses an argument starting with the long option prefix "--"
	 * @param   string $arg
	 * @return  $this
	 * @throws
	 */
	public function parseLongOption($arg) {

		//get the name and value
		if (($separator = strpos($arg, '=')) !== false) {

			//get the option name and value
			$name   = substr($arg, 2, $separator - 2);
			$value  = strlen($arg) > $separator+1 ? substr($arg, $separator+1) : '';

			//get the option
			$option = $this->definition->getOption($name);

			//check the option exists
			if (is_null($option)) {
				throw new ParserException("Option \"$name\" doesn't exist.");
			}

			//check a value is allowed
			if (!$option->isValueAllowed()) {
				throw new ParserException("Option \"$name\" is not permitted to have a value.");
			}

		} else {

			//get the option name
			$name = substr($arg, 2);

			//get the option
			$option = $this->definition->getOption($name);

			//check the option exists
			if (is_null($option)) {
				throw new ParserException("Option \"$name\" doesn't exist.");
			}

			//check if a value is required
			if ($option->isValueRequired()) {
				throw new ParserException("Option \"$name\" must have a value.");
			}

			//set the option value
			$value = $option->getDefault();

		}

		//add to the array
		if ($option->isValueArray()) {
			$array      = (array) $this->options->getOption($name, array());
			$array[]    = $value;
			$value      = $array;
		}

		//set the option value
		$this->options->setOption($name, $value);

		return $this;
	}

	/**
	 * Parses an argument starting with the short option prefix "-"
	 * @param   string $arg
	 * @return  $this
	 * @throws
	 */
	public function parseShortOption($arg) {

		$i = 1;
		$len = strlen($arg);

		while ($i < $len) {

			//get the shortcut
			$shortcut = substr($arg, $i, 1);

			//get the option
			$option = $this->definition->getOptionByShortcut($shortcut);

			//check the option exists
			if (is_null($option)) {
				throw new ParserException("Shortcut option \"$shortcut\" doesn't exist.");
			}

			//check for a value
			if (substr($arg, $i+1, 1) == '=') {

				//get the value
				$value = strlen($arg) > $i+2 ? substr($arg, $i+2) : '';

				//check a value is allowed
				if (!$option->isValueAllowed()) {
					throw new ParserException("Shortcut option \"$shortcut\" is not permitted to have a value.");
				}

				//add to the array
				if ($option->isValueArray()) {
					$array      = (array) $this->options->getOption($option->getName(), array());
					$array[]    = $value;
					$value      = $array;
				}

				//set the option value
				$this->options->setOption($option->getName(), $value);

				//we've finished reading the argument
				$i=$len;
				break;

			} else {

				//check if a value is required
				if ($option->isValueRequired()) {
					throw new ParserException("Shortcut option \"$shortcut\" must have a value.");
				}

				//get the option value
				$value = $option->getDefault();

				//add to the array
				if ($option->isValueArray()) {
					$array      = (array) $this->options->getOption($option->getName(), array());
					$array[]    = $value;
					$value      = $array;
				}

				//set the option value
				$this->options->setOption($option->getName(), $value);

			}

			++$i;
		}

		return $this;
	}

} 