<?php

namespace deit\console;
use deit\console\definition\Definition;
use deit\console\definition\Option;

ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Australia/Sydney');

include __DIR__.'/bootstrap.php';

$option1 = new Option('since');
$option1
	->setMode(Option::OPTION_REQUIRED | Option::VALUE_REQUIRED)
	->setFilter(function($value) {
		return strtotime($value);
	})
;

$option2 = new Option('directory');
$option2
	->setMode(Option::VALUE_REQUIRED)
	->setDefault(getcwd())
	->setValidator(function($value) {
		return is_dir($value);
	})
;

$definition = new Definition();
$definition
	->setName('MyCmd')
	->setDescription('A test command')
	->addOption($option1)
	->addOption($option2)
;

$console = new StandardConsole();
$console
	->setOption('since', '2013-11-27')
;

$definition->validate($console);
var_dump($console);
echo date('Y-m-d', $console->getOption('since'));

echo 'DONE';

return;

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