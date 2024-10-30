<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableIntegrationTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializationFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\InitializableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\ParentInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Initialization integration trait for initializing children in the same-go.
 *
 * @since   1.0.0
 * @version 1.4.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy\Actions
 */
trait InitializeChildrenTrait {

	// region TRAITS
	use InitializableIntegrationTrait;
	// endregion
	// region METHODS
	/**
	 * Makes one's own successful initialization dependent on that of one's children.
	 *
	 * @since   1.0.0
	 * @version 1.4.0
	 *
	 * @return  InitializationFailureException|null
	 */
	protected function integrate_initialize_children() : ?InitializationFailureException {
		$init_result = null;
		if ( $this instanceof ParentInterface ) {
			$children = $this->get_children();
			foreach ( $children as $child ) {
				if ( $child instanceof InitializableInterface ) {
					$init_result = $child->initialize();
					if ( ! \is_null( $init_result ) ) {
						break;
					}
				}
			}
		}
		return $init_result;
	}
	// endregion
}
