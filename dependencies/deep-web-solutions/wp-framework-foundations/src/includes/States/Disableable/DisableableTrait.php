<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Disableable;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionExtensionHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionLocalExtensionHelpersTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the disableable interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\States\Disableable
 */
trait DisableableTrait {

	// region TRAITS
	use ActionExtensionHelpersTrait;
	use ActionLocalExtensionHelpersTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * Whether the using instance is disabled or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool|null
	 */
	protected ?bool $is_disabled = null;
	// endregion
	// region METHODS
	/**
	 * Simple disablement logic.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function is_disabled() : bool {
		if ( \is_null( $this->is_disabled ) ) {
			$this->is_disabled = $this->maybe_execute_local_trait( DisabledLocalTrait::class, 'is_disabled', \false );
			$this->is_disabled = $this->is_disabled || $this->maybe_execute_extension_traits( DisableableExtensionTrait::class, \false, 'is' );
		}
		return $this->is_disabled;
	}
	// endregion
}
