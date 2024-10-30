<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionExtensionHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionLocalExtensionHelpersTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the initializable interface.
 *
 * @since   1.0.0
 * @version 1.4.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Initializable
 */
trait InitializableTrait {

	// region TRAITS
	use ActionExtensionHelpersTrait;
	use ActionLocalExtensionHelpersTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * The result of the initialize operation. Null if successful.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     InitializationFailureException|null
	 */
	protected ?InitializationFailureException $initialization_result;
	/**
	 * Whether the using instance is initialized or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool|null
	 */
	protected ?bool $is_initialized = null;
	// endregion
	// region GETTERS
	/**
	 * Returns the result of the initialization operation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  InitializationFailureException|null
	 */
	public function get_initialization_result() : ?InitializationFailureException {
		return $this->initialization_result;
	}
	/**
	 * Returns whether the using instance is initialized or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool|null
	 */
	public function is_initialized() : ?bool {
		return $this->is_initialized;
	}
	// endregion
	// region METHODS
	/**
	 * Simple initialization logic.
	 *
	 * @since   1.0.0
	 * @version 1.4.0
	 */
	public function initialize() : ?InitializationFailureException {
		if ( \is_null( $this->is_initialized ) ) {
			if ( ! \is_null( $result = $this->maybe_execute_local_trait( InitializeLocalTrait::class, 'initialize' ) ) ) {
                // phpcs:ignore
                $this->is_initialized = \false;
				$this->initialization_result = $result;
			} elseif ( ! \is_null( $result = $this->maybe_execute_extension_traits( InitializableExtensionTrait::class ) ) ) {
                // phpcs:ignore
                $this->is_initialized = \false;
				$this->initialization_result = $result;
			} elseif ( ! \is_null( $result = $this->maybe_execute_extension_traits( InitializableIntegrationTrait::class, null, 'integrate' ) ) ) {
                // phpcs:ignore
                $this->is_initialized = \false;
				$this->initialization_result = $result instanceof InitializationFailureException ? $result : new InitializationFailureException( $result->getMessage(), $result->getCode(), $result );
			} else {
				$this->is_initialized        = \true;
				$this->initialization_result = null;
			}
		} else {
			return new InitializationFailureException( \sprintf( 'Instance of %s has been initialized already', __CLASS__ ) );
		}
		return $this->initialization_result;
	}
	// endregion
}
