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
	protected $options    = [];

	/**
	 * The arguments
	 * @var     mixed[string]
	 */
	protected $arguments  = [];

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
		return array_key_exists($name, $this->options);
	}

	/**
	 * @inheritdoc
	 */
	public function getOption($name, $value = null) {
		if (array_key_exists($name, $this->options)) {
			return $this->options[$name];
		} else {
			return $value;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function setOption($name, $value = null) {
		$this->options[$name] = $value;
		return $this;
	}

} 