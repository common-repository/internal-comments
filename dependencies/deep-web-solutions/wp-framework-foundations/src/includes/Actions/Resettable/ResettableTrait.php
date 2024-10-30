<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunnableTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionExtensionHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionLocalExtensionHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Objects;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the resettable interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Resettable
 */
trait ResettableTrait {

	// region TRAITS
	use ActionExtensionHelpersTrait;
	use ActionLocalExtensionHelpersTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * The result of the reset operation. Null if successful.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     ResetFailureException|null
	 */
	protected ?ResetFailureException $reset_result;
	/**
	 * Whether the using instance is reset or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool|null
	 */
	protected ?bool $is_reset = null;
	// endregion
	// region GETTERS
	/**
	 * Returns the result of the reset operation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ResetFailureException|null
	 */
	public function get_reset_result() : ?ResetFailureException {
		return $this->reset_result;
	}
	/**
	 * Returns whether the using instance is reset or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool|null
	 */
	public function is_reset() : ?bool {
		return $this->is_reset;
	}
	// endregion
	// region METHODS
	/**
	 * Simple reset logic.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function reset() : ?ResetFailureException {
		if ( \is_null( $this->is_reset ) ) {
			if ( ! \is_null( $result = $this->maybe_execute_local_trait( ResetLocalTrait::class, 'reset' ) ) ) {
                // phpcs:ignore
                $this->is_reset = \false;
				$this->reset_result = $result;
			} elseif ( ! \is_null( $result = $this->maybe_execute_extension_traits( ResettableExtensionTrait::class ) ) ) {
                // phpcs:ignore
                $this->is_reset = \false;
				$this->reset_result = $result;
			} else {
				$this->is_reset     = \true;
				$this->reset_result = null;
			}
			if ( $this instanceof RunnableInterface && Objects::has_trait_deep( RunnableTrait::class, $this ) ) {
				$this->is_run = null;
				unset( $this->run_result );
			}
		} else {
			return new ResetFailureException( \sprintf( 'Instance of %s has been reset already', __CLASS__ ) );
		}
		return $this->reset_result;
	}
	// endregion
}
