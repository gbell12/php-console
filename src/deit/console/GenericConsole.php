<?php

namespace deit\console;
use deit\stream\StringInputStream;
use deit\stream\StringOutputStream;

/**
 * Generic console
 * @author James Newell <james@digitaledgeit.com.au>
 */
class GenericConsole extends AbstractConsole {

	/**
	 * Constructs the console
	 */
	public function __construct() {

		//create the std streams
		$this->stdin  = new StringInputStream('');
		$this->stdout = new StringOutputStream();
		$this->stderr = new StringOutputStream();

	}

} 