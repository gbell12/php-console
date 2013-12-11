<?php

namespace deit\console;
use deit\stream\PhpInputStream;
use deit\stream\PhpOutputStream;
use deit\console\parser\ArgvParser;

/**
 * Console
 * @author James Newell <james@digitaledgeit.com.au>
 */
class StandardConsole extends AbstractConsole {

	/**
	 * Constructs the console
	 * @param   string[]  $argv   The command line arguments
	 */
	public function __construct(array $argv = null) {

		//create the std streams
		$this->stdin  = new PhpInputStream(STDIN, false);
		$this->stdout = new PhpOutputStream(STDOUT, false);
		$this->stderr = new PhpOutputStream(STDERR, false);

		//parse the arguments
		$parser = new ArgvParser($argv);
		$parser->parse($this);

	}

} 