<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging;

use DWS_IC_Deps\Psr\Log\LoggerInterface;
use DWS_IC_Deps\Psr\Log\NullLogger;
\defined( 'ABSPATH' ) || exit;
/**
 * Logs messages using an external PSR-3 logger.
 *
 * @since   1.0.0
 * @version 1.5.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Logging
 */
class ExternalLoggerHandler extends AbstractLoggingHandler {

	// region FIELDS AND CONSTANTS
	/**
	 * Instance of a PSR-3 conform logger.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     LoggerInterface
	 */
	protected LoggerInterface $logger;
	// endregion
	// region MAGIC METHODS
	/**
	 * LoggingHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.5.0
	 *
	 * @param   string              $handler_id     Unique name for the logging handler / logger itself.
	 * @param   LoggerInterface     $logger         Instance of a PSR-3 conform logger.
	 */
	public function __construct( string $handler_id, LoggerInterface $logger ) {
		parent::__construct( $handler_id );
		$this->logger = $logger;
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function emergency( $message, array $context = array() ) {
		$this->logger->emergency( $message, $context );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function alert( $message, array $context = array() ) {
		$this->logger->alert( $message, $context );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function critical( $message, array $context = array() ) {
		$this->logger->critical( $message, $context );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function error( $message, array $context = array() ) {
		$this->logger->error( $message, $context );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function warning( $message, array $context = array() ) {
		$this->logger->warning( $message, $context );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function notice( $message, array $context = array() ) {
		$this->logger->notice( $message, $context );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function info( $message, array $context = array() ) {
		$this->logger->info( $message, $context );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function debug( $message, array $context = array() ) {
		$this->logger->debug( $message, $context );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function log( $level, $message, array $context = array() ) {
		$this->logger->log( $level, $message, $context );
	}
	// endregion
}
