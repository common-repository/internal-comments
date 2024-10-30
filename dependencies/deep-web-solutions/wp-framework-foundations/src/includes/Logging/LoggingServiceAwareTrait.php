<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging;

use DWS_IC_Deps\Psr\Log\LogLevel;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for working with the logging service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Logging
 */
trait LoggingServiceAwareTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Logging service for handling logging.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     LoggingService
	 */
	protected LoggingService $logging_service;
	// endregion
	// region GETTERS
	/**
	 * Gets the logging service instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  LoggingService
	 */
	public function get_logging_service() : LoggingService {
		return $this->logging_service;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets the logging service instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   LoggingService      $logging_service     The logging service instance to use from now on.
	 */
	public function set_logging_service( LoggingService $logging_service ) {
		$this->logging_service = $logging_service;
	}
	// endregion
	// region METHODS
	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $message    The log message.
	 * @param   array   $context    The context to pass along to the logger.
	 * @param   string  $handler    The logging handler to log the event with.
	 *
	 * @return  LogMessageBuilder
	 */
	public function log_event( string $message, array $context = array(), string $handler = 'plugin' ) : LogMessageBuilder {
		return $this->get_logging_service()->log_event( $message, $context, $handler );
	}
	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $message    The log message.
	 * @param   array   $context    The context to pass along to the logger.
	 * @param   string  $log_level  The log level of the message.
	 * @param   string  $handler    The logging handler to log the event with.
	 */
	public function log_event_and_finalize( string $message, array $context = array(), string $log_level = LogLevel::DEBUG, string $handler = 'plugin' ) : void {
		$this->get_logging_service()->log_event_and_finalize( $message, $context, $log_level, $handler );
	}
	// endregion
}
