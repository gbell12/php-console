<?php

namespace deit\console\parser;
use deit\console\Event;

/**
 * Console parser
 * @author James Newell <james@digitaledgeit.com.au>
 */
interface ParserInterface {

	/**
	 * Parses the input
	 * @param   Event           $event    The event
	 * @return  $this
	 * @throws
	 */
	public function parse(Event $event);

} 