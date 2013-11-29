<?php

namespace deit\console\parser;
use deit\console\ConsoleInterface;

/**
 * Console parser
 * @author James Newell <james@digitaledgeit.com.au>
 */
interface ParserInterface {

	/**
	 * Parses the input
	 * @param   ConsoleInterface    $console    The console
	 * @return  $this
	 * @throws
	 */
	public function parse(ConsoleInterface $console);

} 