<?php

namespace deit\console;
use deit\console\option\Option;
use deit\console\option\Options;

include __DIR__.'/bootstrap.php';

class MyCmd extends Command {

	public function configure() {
		$this->getDefinition()
			->setName('MyCmd')
			->setDescription('A test command')
			->addOption(new Option('clean'))
			->addOption(new Option('output', 'o', Option::OPTION_REQUIRED | Option::VALUE_REQUIRED))
		;
	}

	/**
	 * @inheritdoc
	 */
	public function execute(Options $options) {
		$this->getOutputStream()->write('test');
	}

}

exit(MyCmd::run(array(
	'--clean',
	'--output=c:\uf-dist',
	//'--test'
)));

/*
$opt = new Options();
$opt
	->setOption('clean')
	->setOption('output', 'c:\uf-dist')
;

exit(MyCmd::run($opt));

*/
