<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\Psr\Log\LogLevel;
\defined( 'ABSPATH' ) || exit;
/**
 * Log message builder.
 *
 * @since   1.0.0
 * @version 1.7.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Logging
 */
class LogMessageBuilder {

	// region FIELDS AND CONSTANTS
	/**
	 * Handler to log the messages with.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     LoggingHandlerInterface
	 */
	protected LoggingHandlerInterface $handler;
	/**
	 * Whether to finalize messages marked sensitive or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool
	 */
	protected bool $log_sensitive;
	/**
	 * The message to log.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     string
	 */
	protected string $message;
	/**
	 * Additional context to pass along.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $context;
	/**
	 * The level of the log message.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	protected string $log_level = LogLevel::DEBUG;
	/**
	 * The function the was called incorrectly.
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     string|null
	 */
	protected ?string $doing_it_wrong_function = null;
	/**
	 * The plugin/framework version that introduced this warning message.
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     string|null
	 */
	protected ?string $doing_it_wrong_version = null;
	/**
	 * Throwable object to return on finalization.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     \Throwable|null
	 */
	protected ?\Throwable $return_throwable = null;
	// endregion
	// region MAGIC METHODS
	/**
	 * LogMessageBuilder constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   LoggingHandlerInterface     $handler            Handler to log the messages with.
	 * @param   bool                        $log_sensitive      Whether to finalize messages marked sensitive or not.
	 * @param   string                      $message            The message to log.
	 * @param   array                       $context            Additional context to pass along.
	 */
	public function __construct( LoggingHandlerInterface $handler, bool $log_sensitive, string $message, array $context = array() ) {
		$this->handler       = $handler;
		$this->log_sensitive = $log_sensitive;
		$this->message       = $this->maybe_redact_sensitive_content( $this->sanitize_outputtable( $message ) );
		$this->context       = $context;
	}
	// endregion
	// region METHODS
	/**
	 * Changes the log level of the message.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $log_level  The new log level of the message.
	 *
	 * @return  $this
	 */
	public function set_log_level( string $log_level ) : LogMessageBuilder {
		$this->log_level = $log_level;
		return $this;
	}
	/**
	 * Marks the message as requiring a call to '_doing_it_wrong' as well.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $function   The function the was called incorrectly.
	 * @param   string  $version    The plugin/framework version that introduced this warning message.
	 *
	 * @return  $this
	 */
	public function doing_it_wrong( string $function, string $version ) : LogMessageBuilder {
		$this->doing_it_wrong_function = $this->sanitize_outputtable( $function );
		$this->doing_it_wrong_version  = $this->sanitize_outputtable( $version );
		return $this;
	}
	/**
	 * Sets the throwable object to return on finalization.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   \Throwable  $throwable  Throwable object to return on finalization.
	 *
	 * @return  $this
	 */
	public function return_throwable( \Throwable $throwable ) : LogMessageBuilder {
		$this->return_throwable = $throwable;
		return $this;
	}
	/**
	 * Instantiates a new exception to return on finalization.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $exception_class    The class of the exception to instantiate.
	 * @param   \Throwable|null     $original           Original throwable object, if one exists.
	 *
	 * @return  $this
	 */
	public function return_exception( string $exception_class, ?\Throwable $original = null ) : LogMessageBuilder {
		if ( \is_a( $exception_class, \Exception::class, \true ) ) {
			$this->return_throwable = new $exception_class( $this->message, \is_null( $original ) ? 0 : $original->getCode(), $original );
		}
		return $this;
	}
	/**
	 * Performs all the actions as per the object's configuration.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  null|\Throwable
	 */
	public function finalize() : ?\Throwable {
		$this->handler->log( $this->log_level, $this->message, $this->context );
		if ( ! \is_null( $this->doing_it_wrong_function ) && ! \is_null( $this->doing_it_wrong_version ) ) {
			\_doing_it_wrong( $this->doing_it_wrong_function, $this->message, $this->doing_it_wrong_version );
            // phpcs:ignore
        }
		return $this->return_throwable;
	}
	// endregion
	// region HELPERS
	/**
	 * Sanitizes a string that will be outputted somewhere.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $outputtable    String that could be outputted.
	 *
	 * @return  string
	 */
	protected function sanitize_outputtable( string $outputtable ) : string {
		return \wp_kses(
			$outputtable,
			array(
				'p'         => array(),
				'strong'    => array(),
				'br'        => array(),
				'sensitive' => array(),
			)
		);
	}
	/**
	 * Based on privacy settings, maybe redact sensitive parts of the message out.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @param   string  $message    Message to redact.
	 *
	 * @return  string
	 */
	protected function maybe_redact_sensitive_content( string $message ) : string {
		if ( \false === $this->log_sensitive ) {
			$message = \preg_replace( '#(<sensitive>).*?(</sensitive>)#', '$1REDACTED FOR PRIVACY$2', $message );
		} else {
			/* @noinspection HtmlUnknownTag */
			$message = Strings::replace_placeholders(
				$message,
				array(
					'<sensitive>'  => '',
					'</sensitive>' => '',
				)
			);
		}
		return $message;
	}
	// endregion
}
