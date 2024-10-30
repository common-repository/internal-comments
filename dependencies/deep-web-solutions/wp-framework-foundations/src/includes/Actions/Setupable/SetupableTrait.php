<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionExtensionHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionLocalExtensionHelpersTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the setupable interface.
 *
 * @since   1.0.0
 * @version 1.4.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Setupable
 */
trait SetupableTrait {

	// region TRAITS
	use ActionExtensionHelpersTrait;
	use ActionLocalExtensionHelpersTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * The result of the setup operation. Null if successful.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     SetupFailureException|null
	 */
	protected ?SetupFailureException $setup_result;
	/**
	 * Whether the using instance is setup or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool|null
	 */
	protected ?bool $is_setup = null;
	// endregion
	// region GETTERS
	/**
	 * Returns the result of the setup operation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  SetupFailureException|null
	 */
	public function get_setup_result() : ?SetupFailureException {
		return $this->setup_result;
	}
	/**
	 * Returns whether the using instance is setup or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool|null
	 */
	public function is_setup() : ?bool {
		return $this->is_setup;
	}
	// endregion
	// region METHODS
	/**
	 * Simple setup logic.
	 *
	 * @since   1.0.0
	 * @version 1.4.0
	 */
	public function setup() : ?SetupFailureException {
		if ( \is_null( $this->is_setup ) ) {
			if ( ! \is_null( $result = $this->maybe_execute_local_trait( SetupLocalTrait::class, 'setup' ) ) ) {
                // phpcs:ignore
                $this->is_setup = \false;
				$this->setup_result = $result;
			} elseif ( ! \is_null( $result = $this->maybe_execute_extension_traits( SetupableExtensionTrait::class ) ) ) {
                // phpcs:ignore
                $this->is_setup = \false;
				$this->setup_result = $result;
			} elseif ( ! \is_null( $result = $this->maybe_execute_extension_traits( SetupableIntegrationTrait::class, null, 'integrate' ) ) ) {
                // phpcs:ignore
                $this->is_setup = \false;
				$this->setup_result = $result instanceof SetupFailureException ? $result : new SetupFailureException( $result->getMessage(), $result->getCode(), $result );
			} else {
				$this->is_setup     = \true;
				$this->setup_result = null;
			}
		} else {
			return new SetupFailureException( \sprintf( 'Instance of %s has been setup already', __CLASS__ ) );
		}
		return $this->setup_result;
	}
	// endregion
}
