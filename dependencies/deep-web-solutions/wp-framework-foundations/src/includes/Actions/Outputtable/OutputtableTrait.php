<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionExtensionHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionLocalExtensionHelpersTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the outputtable interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Outputtable
 */
trait OutputtableTrait {

	// region TRAITS
	use ActionExtensionHelpersTrait;
	use ActionLocalExtensionHelpersTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * The result of the output operation. Null if successful.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     OutputFailureException|null
	 */
	protected ?OutputFailureException $output_result;
	/**
	 * Whether the using instance is outputted or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool|null
	 */
	protected ?bool $is_outputted = null;
	// endregion
	// region GETTERS
	/**
	 * Returns the result of the output operation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  OutputFailureException|null
	 */
	public function get_output_result() : ?OutputFailureException {
		return $this->output_result;
	}
	/**
	 * Returns whether the using instance is outputted or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool|null
	 */
	public function is_outputted() : ?bool {
		return $this->is_outputted;
	}
	// endregion
	// region METHODS
	/**
	 * Simple output logic.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function output() : ?OutputFailureException {
		if ( \is_null( $this->is_outputted ) ) {
			if ( ! \is_null( $result = $this->maybe_execute_local_trait( OutputLocalTrait::class, 'output' ) ) ) {
                // phpcs:ignore
                $this->is_outputted = \false;
				$this->output_result = $result;
			} elseif ( ! \is_null( $result = $this->maybe_execute_extension_traits( OutputtableExtensionTrait::class ) ) ) {
                // phpcs:ignore
                $this->is_outputted = \false;
				$this->output_result = $result;
			} else {
				$this->is_outputted  = \true;
				$this->output_result = null;
			}
		} else {
			return new OutputFailureException( \sprintf( 'Instance of %s has been outputted already', __CLASS__ ) );
		}
		return $this->output_result;
	}
	// endregion
}
