<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\States;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\ChildInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Disableable\DisableableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\DisableableInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Disableable extension trait for making the disablement state dependent on the parent's disablement state.
 *
 * @since   1.0.0
 * @version 1.1.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy\States
 */
trait DisabledParentTrait {

	// region TRAITS
	use DisableableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * Makes child disablement state dependent on the parent's disablement state.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function is_disabled_parent() : bool {
		$is_disabled = \false;
		if ( $this instanceof ChildInterface ) {
			$parent = $this->get_parent();
			if ( $parent instanceof DisableableInterface ) {
				$is_disabled = $parent->is_disabled();
			}
		}
		return $is_disabled;
	}
	// endregion
}
