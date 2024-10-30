<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating some of the most often needed functionality of a dependencies checker.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies
 */
abstract class AbstractDependenciesChecker extends AbstractHandler implements DependenciesCheckerInterface {

	// region FIELDS AND CONSTANTS
	/**
	 * List of dependencies to check for.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     array
	 */
	protected array $dependencies = array();
	// endregion
	// region MAGIC METHODS
	/**
	 * AbstractDependenciesChecker constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $checker_id     The ID of the checker.
	 * @param   array   $dependencies   List of dependencies to check for.
	 */
	public function __construct( string $checker_id, array $dependencies = array() ) {
		parent::__construct( $checker_id );
		foreach ( $dependencies as $key => $config ) {
			if ( \is_string( $key ) && \is_array( $config ) ) {
				$this->register_dependency( $config + array( $this->get_dependency_key() => $key ) );
			} else {
				$this->register_dependency( $config );
			}
		}
	}
	// endregion
	// region GETTERS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_dependencies() : array {
		return $this->dependencies;
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_dependency( $dependency ) : bool {
		$validity = $this->is_dependency_valid( $dependency );
		if ( \true === $validity ) {
			$this->dependencies[] = $dependency;
		}
		return $validity;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function are_dependencies_fulfilled() : bool {
		return empty( $this->get_missing_dependencies() );
	}
	// endregion
	// region HELPERS
	/**
	 * Checks whether the dependency is valid for the current checker.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $dependency     Dependency to check.
	 *
	 * @return  bool
	 */
	protected function is_dependency_valid( $dependency ) : bool {
		return \is_string( $dependency );
	}
	/**
	 * For dependencies passed on as an associative array, this determines the name of the key's key within
	 * the array passed on to 'register_dependency'.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	protected function get_dependency_key() : string {
		return 'key';
	}
	// endregion
}
