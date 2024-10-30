<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Checkers\WPPluginsChecker;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\DependencyContextsEnum;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Handlers\SingleCheckerHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\States\ActiveDependenciesTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\States\DisabledDependenciesTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait for initializing plugin-only dependencies handlers for all known contexts.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies\Actions
 */
trait InitializePluginDependenciesContextHandlersTrait {

	// region TRAITS
	use ActiveDependenciesTrait;
	use DisabledDependenciesTrait;
	use InitializeDependenciesHandlersTrait;
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_dependencies_handlers() : array {
		$handlers = array();
		foreach ( DependencyContextsEnum::get_all() as $context ) {
			$plugin_dependencies = $this->get_plugin_dependencies( $context );
			$plugin_dependencies = Arrays::has_string_keys( $plugin_dependencies ) ? array( $plugin_dependencies ) : $plugin_dependencies;
			$handler_id          = $this->get_dependencies_handler_id( $context );
			$checker             = new WPPluginsChecker( $handler_id, $plugin_dependencies );
			$handlers[]          = new SingleCheckerHandler( $handler_id, $checker );
		}
		return $handlers;
	}
	// endregion
	// region METHODS
	/**
	 * Returns the plugin dependencies of the class in the format expected by the WPPluginsChecker class.
	 * Can return either a single dependency or an array of dependencies.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $context    The dependencies handler context.
	 *
	 * @return  array|array[]
	 */
	protected function get_plugin_dependencies( string $context ) : array {
		$return      = array();
		$method_name = "get_plugin_dependencies_{$context}";
		if ( \method_exists( $this, $method_name ) ) {
			$return = $this->{$method_name}();
		}
		return Arrays::validate( $return, array() );
	}
	// endregion
}
