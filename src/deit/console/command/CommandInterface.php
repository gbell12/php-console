<?php

namespace deit\console\command;
use deit\console\Event;

/**
 * Command
 * @author James Newell <james@digitaledgeit.com.au>
 */
interface CommandInterface {

	/**
	 * The command main method
	 * @param   Event           $event          The console event
	 */
	public function dispatch(Event $event);

} 