<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable\ResettableTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\ResettableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionExtensionHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionLocalExtensionHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Objects;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the runnable interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Runnable
 */
trait RunnableTrait {

	// region TRAITS
	use ActionExtensionHelpersTrait;
	use ActionLocalExtensionHelpersTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * The result of the run operation. Null if successful.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     RunFailureException|null
	 */
	protected ?RunFailureException $run_result;
	/**
	 * Whether the using instance is ran or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool|null
	 */
	protected ?bool $is_run = null;
	// endregion
	// region GETTERS
	/**
	 * Returns the result of the run operation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  RunFailureException|null
	 */
	public function get_run_result() : ?RunFailureException {
		return $this->run_result;
	}
	/**
	 * Returns whether the using instance is ran or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool|null
	 */
	public function is_run() : ?bool {
		return $this->is_run;
	}
	// endregion
	// region METHODS
	/**
	 * Simple run logic.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function run() : ?RunFailureException {
		if ( \is_null( $this->is_run ) ) {
			if ( ! \is_null( $result = $this->maybe_execute_local_trait( RunLocalTrait::class, 'run' ) ) ) {
                // phpcs:ignore
                $this->is_run = \false;
				$this->run_result = $result;
			} elseif ( ! \is_null( $result = $this->maybe_execute_extension_traits( RunnableExtensionTrait::class ) ) ) {
                // phpcs:ignore
                $this->is_run = \false;
				$this->run_result = $result;
			} else {
				$this->is_run     = \true;
				$this->run_result = null;
			}
			if ( $this instanceof ResettableInterface && Objects::has_trait_deep( ResettableTrait::class, $this ) ) {
				$this->is_reset = null;
				unset( $this->reset_result );
			}
		} else {
			return new RunFailureException( \sprintf( 'Instance of %s has been run already', __CLASS__ ) );
		}
		return $this->run_result;
	}
	// endregion
}
