<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an object that checks whether a list of dependencies is fulfilles or not.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies
 */
interface DependenciesCheckerInterface extends DependenciesHandlerInterface {

	/**
	 * Adds a dependency to the list of dependencies to check for.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $dependency     The dependency to check for.
	 */
	public function register_dependency( $dependency);
}
