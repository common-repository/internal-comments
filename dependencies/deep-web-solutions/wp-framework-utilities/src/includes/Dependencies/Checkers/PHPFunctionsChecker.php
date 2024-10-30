<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Checkers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\AbstractDependenciesChecker;
\defined( 'ABSPATH' ) || exit;
/**
 * Checks whether a list of PHP functions is present or not.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Dependencies\Checkers
 */
class PHPFunctionsChecker extends AbstractDependenciesChecker {

	// region GETTERS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_type() : string {
		return 'php_functions';
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_missing_dependencies() : array {
		return \array_filter(
			\array_map(
				function ( $php_function ) {
					return \function_exists( $php_function ) ? \false : $php_function;
				},
				$this->get_dependencies()
			)
		);
	}
	// endregion
}
