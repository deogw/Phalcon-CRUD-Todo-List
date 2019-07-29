<?php
/**
 * Created by PhpStorm.
 * User: gamalan
 * Date: 4/6/18
 * Time: 7:53 AM
 */

namespace Application\Exception\Handler;

use CrazyFactory\PhalconLogger\Adapter\Sentry;
use Phalcon\Logger;
use Whoops\Exception\Frame;
use Whoops\Handler\Handler;

/**
 * Application\Exception\Handler\LoggerHandler
 *
 * @package Application\Error\Handler
 */
class LoggerHandler extends Handler {
	const VAR_DUMP_PREFIX = '   | ';

	/**
	 * @var bool
	 */
	private $addTraceToOutput = true;

	/**
	 * @var bool|integer
	 */
	private $addTraceFunctionArgsToOutput = false;

	/**
	 * @var integer
	 */
	private $traceFunctionArgsOutputLimit = 1024;

	/**
	 * {@inheritdoc}
	 *
	 * @return int|null
	 */
	public function handle() {
		$exception = $this->getException();
		$response  = $this->generateResponse();

		if ( container()->has( 'sentry' ) ) {
			/** @var Sentry $sentry */
			$sentry = container( 'sentry' );
			$sentry->setTag( 'mode', container( 'bootstrap' )->getAppMode() );
			$sentry->logException( $exception,
				[],
				$sentry->getLogLevel() );
		}
		if ( $logger = $this->getLogger() ) {
			$logger->error( $response );
		}


		return Handler::DONE;
	}

	/**
	 * Create plain text response and return it as a string.
	 *
	 * @return string
	 */
	public function generateResponse() {
		/** @var \ErrorException $exception */
		$exception = $this->getException();

		return sprintf(
			"%s: %s in file %s on line %d%s\n",
			get_class( $exception ),
			$exception->getMessage(),
			$exception->getFile(),
			$exception->getLine(),
			$this->getTraceOutput()
		);
	}

	/**
	 * Add error trace function arguments to output.
	 * Set to True for all frame args, or integer for the n first frame args.
	 *
	 * @param  bool|integer|null $addTraceFunctionArgsToOutput
	 *
	 * @return null|bool|integer
	 */
	public function addTraceFunctionArgsToOutput( $addTraceFunctionArgsToOutput = null ) {
		if ( func_num_args() == 0 ) {
			return $this->addTraceFunctionArgsToOutput;
		}

		if ( ! is_integer( $addTraceFunctionArgsToOutput ) ) {
			$this->addTraceFunctionArgsToOutput = (bool) $addTraceFunctionArgsToOutput;
		} else {
			$this->addTraceFunctionArgsToOutput = $addTraceFunctionArgsToOutput;
		}
	}

	/**
	 * Get the size limit in bytes of frame arguments var_dump output.
	 * If the limit is reached, the var_dump output is discarded.
	 * Prevent memory limit errors.
	 *
	 * @return integer
	 */
	public function getTraceFunctionArgsOutputLimit() {
		return $this->traceFunctionArgsOutputLimit;
	}

	/**
	 * Add error trace to output.
	 *
	 * @param  bool|null $addTraceToOutput
	 *
	 * @return bool|$this
	 */
	public function addTraceToOutput( $addTraceToOutput = null ) {
		if ( func_num_args() == 0 ) {
			return $this->addTraceToOutput;
		}

		$this->addTraceToOutput = (bool) $addTraceToOutput;

		return $this;
	}

	/**
	 * @return string
	 */
	public function contentType() {
		return 'text/plain';
	}

	private function getLogger() {
		if ( container()->has( 'logger' ) ) {
			return container( 'logger', [ container( 'bootstrap' )->getMode() . "." . container( 'bootstrap' )->getRoute() ] );
		}

		return null;
	}

	/**
	 * Get the exception trace as plain text.
	 *
	 * @return string
	 */
	private function getTraceOutput() {
		if ( ! $this->addTraceToOutput() ) {
			return '';
		}
		$inspector = $this->getInspector();
		$frames    = $inspector->getFrames();

		$response = "\nStack trace:";

		$line = 1;
		foreach ( $frames as $frame ) {
			/** @var Frame $frame */
			$class = $frame->getClass();

			$template = "\n%3d. %s->%s() %s:%d%s";
			if ( ! $class ) {
				// Remove method arrow (->) from output.
				$template = "\n%3d. %s%s() %s:%d%s";
			}

			$response .= sprintf(
				$template,
				$line,
				$class,
				$frame->getFunction(),
				$frame->getFile(),
				$frame->getLine(),
				$this->getFrameArgsOutput( $frame, $line )
			);

			$line ++;
		}

		return $response;
	}

	/**
	 * Get the frame args var_dump.
	 *
	 * @param  Frame $frame
	 * @param  integer $line
	 *
	 * @return string
	 */
	private function getFrameArgsOutput( Frame $frame, $line ) {
		if ( $this->addTraceFunctionArgsToOutput() === false
		     || $this->addTraceFunctionArgsToOutput() < $line
		) {
			return '';
		}

		// Dump the arguments:
		ob_start();
		var_dump( $frame->getArgs() );
		if ( ob_get_length() > $this->getTraceFunctionArgsOutputLimit() ) {
			// The argument var_dump is to big.
			// Discarded to limit memory usage.
			ob_clean();

			return sprintf(
				"\n%sArguments dump length greater than %d Bytes. Discarded.",
				self::VAR_DUMP_PREFIX,
				$this->getTraceFunctionArgsOutputLimit()
			);
		}

		return sprintf(
			"\n%s",
			preg_replace( '/^/m', self::VAR_DUMP_PREFIX, ob_get_clean() )
		);
	}
}