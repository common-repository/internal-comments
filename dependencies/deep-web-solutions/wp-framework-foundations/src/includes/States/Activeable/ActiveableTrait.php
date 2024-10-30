<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Activeable;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionExtensionHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\ActionLocalExtensionHelpersTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the activeable interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\States\Activeable
 */
trait ActiveableTrait {

	// region TRAITS
	use ActionExtensionHelpersTrait;
	use ActionLocalExtensionHelpersTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * Whether the using instance is active or not. Null if not decided yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool|null
	 */
	protected ?bool $is_active = null;
	// endregion
	// region METHODS
	/**
	 * Simple activation logic.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function is_active() : bool {
		if ( \is_null( $this->is_active ) ) {
			$this->is_active = $this->maybe_execute_local_trait( ActiveLocalTrait::class, 'is_active', \true );
			$this->is_active = $this->is_active && $this->maybe_execute_extension_traits( ActiveableExtensionTrait::class, \true, 'is' );
		}
		return $this->is_active;
	}
	// endregion
}
