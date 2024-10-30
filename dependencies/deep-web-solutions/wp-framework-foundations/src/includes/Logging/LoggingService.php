<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\MultiHandlerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\MultiHandlerAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\MultiStoreAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StorableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\Stores\MemoryStore;
use DWS_IC_Deps\Psr\Log\LogLevel;
use DWS_IC_Deps\Psr\Log\NullLogger;
\defined( 'ABSPATH' ) || exit;
/**
 * Logs messages at all PSR-3 levels. GDPR-appropriate + full logger choice flexibility.
 *
 * @since   1.0.0
 * @version 1.5.4
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Logging
 */
class LoggingService implements PluginAwareInterface, StorableInterface, MultiHandlerAwareInterface, MultiStoreAwareInterface {

	// region TRAITS
	use MultiHandlerAwareTrait {
		get_handler as protected get_handler_trait;
		register_handler as protected register_handler_trait;
	}
	use PluginAwareTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * Whether to include sensitive information in the logs or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool
	 */
	protected bool $log_sensitive;
	// endregion
	// region MAGIC METHODS
	/**
	 * LoggingService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.5.3
	 *
	 * @param   PluginInterface             $plugin             The plugin instance.
	 * @param   LoggingHandlerInterface[]   $handlers           Collection of logging handlers to use.
	 * @param   bool                        $include_sensitive  Whether the logs should include sensitive information or not.
	 */
	public function __construct( PluginInterface $plugin, array $handlers = array(), bool $include_sensitive = \false ) {
		$this->set_plugin( $plugin );
		$this->set_handlers_store( new MemoryStore( 'handlers' ) );
		$fallback_logger = new ExternalLoggerHandler( 'null', new NullLogger() );
		$this->set_handlers( \array_merge( array( $fallback_logger ), $handlers ) );
		$this->log_sensitive = $include_sensitive;
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.5.0
	 * @version 1.5.0
	 */
	public function get_id() : string {
		return static::class;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.5.0
	 */
	public function get_handler( string $handler_id ) : LoggingHandlerInterface {
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->get_handler_trait( $handler_id ) ?? $this->get_handler_trait( 'null' );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.5.4
	 *
	 * @throws  \LogicException     Thrown if the handler passed on is of the wrong type.
	 */
	public function register_handler( HandlerInterface $handler ) : LoggingService {
		if ( ! \is_a( $handler, LoggingHandlerInterface::class ) ) {
			throw new \LogicException( \sprintf( 'The handler registered must be of class %s', LoggingHandlerInterface::class ) );
		}
		if ( $handler instanceof PluginAwareInterface ) {
			$handler->set_plugin( $this->get_plugin() );
		}
		return $this->register_handler_trait( $handler );
	}
	// endregion
	// region GETTERS
	/**
	 * Returns whether the logs will include messages marked sensitive or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function should_log_sensitive_messages() : bool {
		return $this->log_sensitive;
	}
	// endregion
	// region METHODS
	/**
	 * Returns a configurable log message object that needs to be finalized.
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
		return new LogMessageBuilder( $this->get_handler( $handler ), $this->should_log_sensitive_messages(), $message, $context );
	}
	/**
	 * Logs an event immediately using the given handler.
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
		$this->log_event( $message, $context, $handler )->set_log_level( $log_level )->finalize();
	}
	// endregion
}
