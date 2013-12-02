<?php

namespace deit\console;
use deit\console\definition\Option;

include __DIR__.'/bootstrap.php';

class MyCmd extends Command {

	public function configure() {

		$clean = new Option('clean');

		$theme = new Option('theme');
		$theme->setMode(Option::OPTION_REQUIRED | Option::VALUE_REQUIRED);

		$content = new Option('content');
		$content->setMode(Option::OPTION_REQUIRED | Option::VALUE_REQUIRED);

		$output = new Option('output');
		$output->setMode(Option::VALUE_REQUIRED);

		$this->getDefinition()
			->setName('MyCmd')
			->setDescription('A test command')
			->addOption($clean)
			->addOption($theme)
			->addOption($content)
			->addOption($output)
		;
	}

	/**
	 * @inheritdoc
	 */
	public function main(AbstractConsole $console) {
		$console->getOutputStream()->write('test');
	}

}

$console = new StandardConsole();
$console
	->setOption('clean', null)
	->setOption('theme', null)
	->setOption('output', 'c:\uf-dist')
;

$app = new Application($console);
$app->addCommand(new MyCmd());
$app->run();

echo "\nDONE\n";