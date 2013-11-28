<?php

namespace deit\console;
use deit\console\option\Options;
use deit\console\option\Definition;
use deit\console\option\Parser;
use deit\console\option\ParserException;
use deit\stream\InputStream;
use deit\stream\OutputStream;
use deit\stream\PhpInputStream;
use deit\stream\PhpOutputStream;

/**
 * Console command
 * @author James Newell <james@digitaledgeit.com.au>
 */
abstract class Command {

	/**
	 * Runs the command
	 * @param   Options|string[string]  $options     The options
	 * @return  int
	 */
	public static function run($options = null) {

		//create the default streams
		$stdin  = new PhpInputStream(STDIN, false);
		$stdout = new PhpOutputStream(STDOUT, false);
		$stderr = new PhpOutputStream(STDERR, false);

		//create the command
		$cmd = new static($stdin, $stdout, $stderr);

		//parse the options
		try {
			if ($options == null) {
				$parser     = new Parser($cmd->getDefinition());
				$options    = $parser->parse($_SERVER['argv']);
			} else if (is_array($options)) {
				$parser     = new Parser($cmd->getDefinition());
				$options    = $parser->parse($options);
			}
		} catch (ParserException $exception) {
			$cmd->getErrorStream()->write($exception->getMessage());
			return -1;
		}

		//execute the command and return the exit code
		try {
			return $cmd->execute($options);
		} catch (\Exception $exception) {
			$cmd->getErrorStream()->write($exception->getTraceAsString());
			return -1;
		}

	}

	/**
	 * The input stream
	 * @var     PhpInputStream
	 */
	private $stdin;

	/**
	 * The output stream
	 * @var     PhpOutputStream
	 */
	private $stdout;

	/**
	 * The error stream
	 * @var     PhpOutputStream
	 */
	private $stderr;

	/**
	 * The cmd line args definition
	 * @var     Definition
	 */
	private $definition;

	/**
	 * Constructs the command
	 */
	public function __construct(InputStream $stdin, OutputStream $stdout, OutputStream $stderror) {

		$this->stdin        = $stdin;
		$this->stdout       = $stdout;
		$this->stderr       = $stderror;
		$this->definition   = new Definition();

		$this->configure();

		//todo: set the definition name if not already set

	}

	/**
	 * Gets the input stream
	 * @return  PhpInputStream
	 */
	public function getInputStream() {
		return $this->stdin;
	}

	/**
	 * Gets the output stream
	 * @return  PhpOutputStream
	 */
	public function getOutputStream() {
		return $this->stdout;
	}

	/**
	 * Gets the error stream
	 * @return  PhpOutputStream
	 */
	public function getErrorStream() {
		return $this->stderr;
	}

	/**
	 * Gets the option definitions
	 * @return  Definition
	 */
	public function getDefinition() {
		return $this->definition;
	}

	/**
	 * Allows the command to configure itself
	 * @return  void
	 */
	public function configure() {
	}

	/**
	 * Executes the command logic
	 * @param   Options     $options        The options
	 * @return  int                         The exit code
	 */
	abstract public function execute(Options $options);

} 