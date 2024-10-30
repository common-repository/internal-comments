<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Helpers;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an object that has helpers for working with dependencies.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies\Helpers
 */
interface DependenciesHelpersAwareInterface {

	/**
	 * Returns a generated handler ID based on dependencies context.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $context    The context of the dependencies handler.
	 *
	 * @return  string
	 */
	public function get_dependencies_handler_id( string $context) : string;
}
