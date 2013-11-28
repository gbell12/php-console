<?php

namespace deit\console\option;

/**
 * Parser test
 * @author James Newell <james@digitaledgeit.com.au>
 */
class ArgvParserTest extends \PHPUnit_Framework_TestCase {

	public function test() {

		$argv = array(
			'tests\test.php',
			'-a',
			'-bc',
		    '-def',
		    '--opt1',
		    'val1',
		    '--opt2=val2',
		    '--opt3=val3.1',
		    '--opt3=val3.2',
			'--opt4=',
		    '--',   //every option after this one should be ignored
			'-c',
		    '-d',
		);

		$d = new Definition();
		$d->addOption(new Option(''))

		$p = new Parser($d);
		$i = $p->parse($argv);

		$this->assertTrue($i->hasOption('a'));
		$this->assertTrue($i->hasOption('b'));
		$this->assertTrue($i->hasOption('c'));
		$this->assertTrue($i->hasOption('d'));
		$this->assertTrue($i->hasOption('e'));
		$this->assertTrue($i->hasOption('f'));

	}

}
 