<?php

namespace deit\console;
use deit\stream\InputStream;
use deit\stream\OutputStream;

/**
 * Console abstract
 * @author James Newell <james@digitaledgeit.com.au>
 */
abstract class AbstractConsole implements ConsoleInterface {

	/**
	 * The stdin
	 * @var     InputStream
	 */
	protected $stdin;

	/**
	 * The stdout
	 * @var     OutputStream
	 */
	protected $stdout;

	/**
	 * The stderr
	 * @var     OutputStream
	 */
	protected $stderr;

	/**
	 * The options
	 * @var     mixed[string]
	 */
	protected $options    = array();

	/**
	 * The arguments
	 * @var     mixed[string]
	 */
	protected $arguments  = array();

	/**
	 * @inheritdoc
	 */
	public function getInputStream() {
		return $this->stdin;
	}

	/**
	 * @inheritdoc
	 */
	public function getOutputStream() {
		return $this->stdout;
	}

	/**
	 * @inheritdoc
	 */
	public function getErrorStream() {
		return $this->stderr;
	}

	/**
	 * @inheritdoc
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @inheritdoc
	 */
	public function setOptions(array $options) {
		$this->options = $options;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function hasOption($name) {
				
		if (is_array($name)) {
			$names = $name;
		} else {
			$names = explode('|', $name, 2);
		}
		
		foreach ($names as $name) {
			if (array_key_exists($name, $this->options)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function getOption($name, $value = null) {
		
		if (is_array($name)) {
			$names = $name;
		} else {
			$names = explode('|', $name, 2);
		}
		
		foreach ($names as $name) {
			if (array_key_exists($name, $this->options)) {
				return $this->options[$name];
			}
		}
		return $value;
	}

	/**
	 * @inheritdoc
	 */
	public function setOption($name, $value = null) {
		$this->options[$name] = $value;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getArguments() {
		return $this->arguments;
	}
	
	/**
	 * @inheritdoc
	 */
	public function setArguments(array $arguments) {
		$this->arguments = $arguments;
		return $this;
	}
	
	/**
	 * @inheritdoc
	 */
	public function hasArgument($i) {
		return array_key_exists($i, $this->arguments);
	}
	
	/**
	 * @inheritdoc
	 */
	public function getArgument($i, $default = null) {
		if (array_key_exists($i, $this->arguments)) {
			return $this->arguments[$i];
		} else {
			return $default;
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function setArgument($i, $value) {
		$this->arguments[$i] = $value;
		return $this;
	}
	
} 