<?php

namespace deit\console;
use deit\stream\InputStream;
use deit\stream\OutputStream;

/**
 * Console interface
 * @author James Newell <james@digitaledgeit.com.au>
 */
interface ConsoleInterface {

	/**
	 * Gets the input stream
	 * @return  InputStream
	 */
	public function getInputStream();

	/**
	 * Gets the output stream
	 * @return  OutputStream
	 */
	public function getOutputStream();

	/**
	 * Gets the error stream
	 * @return  OutputStream
	 */
	public function getErrorStream();

	/**
	 * Gets all option values
	 * @return  mixed[string]
	 */
	public function getOptions();

	/**
	 * Sets all option values
	 * @param   mixed[string] $options
	 * @return  $this
	 */
	public function setOptions(array $options);

	/**
	 * Gets an option value
	 * @param   string  $name    The option name
	 * @return  bool
	 */
	public function hasOption($name);

	/**
	 * Gets an option value
	 * @param   string  $name    The option name
	 * @param   string  $value   The default option value
	 * @return  string
	 */
	public function getOption($name, $value = null);

	/**
	 * Sets an option value
	 * @param   string  $name    The option name
	 * @param   string  $value   The default option value
	 * @return  $this
	 */
	public function setOption($name, $value = null);

} 