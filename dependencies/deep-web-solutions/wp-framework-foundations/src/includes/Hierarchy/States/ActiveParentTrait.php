<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\States;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\ChildInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Activeable\ActiveableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\ActiveableInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Activeable extension trait for making the activation state dependent on the parent's activation state.
 *
 * @since   1.0.0
 * @version 1.1.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy\States
 */
trait ActiveParentTrait {

	// region TRAITS
	use ActiveableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * Makes child activation state dependent on the parent's activation state.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function is_active_parent() : bool {
		$is_active = \true;
		if ( $this instanceof ChildInterface ) {
			$parent = $this->get_parent();
			if ( $parent instanceof ActiveableInterface ) {
				$is_active = $parent->is_active();
			}
		}
		return $is_active;
	}
	// endregion
}
