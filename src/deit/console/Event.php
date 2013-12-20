<?php

namespace deit\console;
use deit\event\EventInterface;
use deit\console\command\CommandInterface;
use deit\stream\InputStream;
use deit\stream\OutputStream;

/**
 * Console event
 * @author James Newell <james@digitaledgeit.com.au>
 */
class Event implements EventInterface {

	/**
	 * The event name
	 * @var     string
	 */
	private $name;

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
	 * Gets the event name
	 * @return  string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name
	 * @param   string $name
	 * @return  $this
	 */
	public function setName($name) {
		$this->name = (string) $name;
		return $this;
	}

	/**
	 * Gets the input stream
	 * @return  InputStream
	 */
	public function getInputStream() {
		return $this->stdin;
	}

	/**
	 * Sets the input stream
	 * @param   InputStream $stream
	 * @return  $this
	 */
	public function setInputStream(InputStream $stream) {
		$this->stdin = $stream;
		return $this;
	}

	/**
	 * Gets the output stream
	 * @return  OutputStream
	 */
	public function getOutputStream() {
		return $this->stdout;
	}

	/**
	 * Sets the output stream
	 * @param   OutputStream $stream
	 * @return  $this
	 */
	public function setOutputStream(OutputStream $stream) {
		$this->stdout = $stream;
		return $this;
	}

	/**
	 * Gets the error stream
	 * @return  OutputStream
	 */
	public function getErrorStream() {
		return $this->stderr;
	}

	/**
	 * Sets the error stream
	 * @param   OutputStream $stream
	 * @return  $this
	 */
	public function setErrorStream(OutputStream $stream) {
		$this->stderr = $stream;
		return $this;
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
 