<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable\ResetFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable\ResetLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\ResettableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\AbstractHooksHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Compatibility layer between the framework and WordPress' API for filters and actions.
 *
 * Maintain a list of all hooks that are registered throughout the plugin, and handles their registration with
 * the WordPress API after calling the run function.
 *
 * @see     https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/blob/master/plugin-name/includes/class-plugin-name-loader.php
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks\Handlers
 */
class BufferedHooksHandler extends AbstractHooksHandler implements RunnableInterface, ResettableInterface {

	// region TRAITS
	use RunLocalTrait;
	use ResetLocalTrait;
	// endregion
	// region MAGIC METHODS
	/**
	 * BufferedHooksHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $handler_id     The ID of the handler instance.
	 */
	public function __construct( string $handler_id = 'buffered' ) {
        // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		parent::__construct( $handler_id );
	}
	// endregion
	// region INHERITED FUNCTIONS
	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  RunFailureException|null
	 */
	protected function run_local() : ?RunFailureException {
		$this->array_walk_hooks( 'array_walk_add_hook' );
		return null;
	}
	/**
	 * De-registers the filters and actions with WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ResetFailureException|null
	 */
	protected function reset_local() : ?ResetFailureException {
		$this->array_walk_hooks( 'array_walk_remove_hook' );
		return null;
	}
	// endregion
	// region HELPERS
	/**
	 * Registers an action/filter with WP.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $hook   Filter to register.
	 *
	 * @return  bool    Whether registration was successful or not.
	 */
	protected function array_walk_add_hook( array $hook ) : bool {
		if ( empty( $hook['component'] ) ) {
			return \add_filter( $hook['hook'], $hook['callback'], $hook['priority'], $hook['accepted_args'] );
		} else {
			return \add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
	}
	/**
	 * Un-registers an action/filter with WP.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $hook   Filter to un-register.
	 *
	 * @return  bool    Whether un-registration was successful or not.
	 */
	protected function array_walk_remove_hook( array $hook ) : bool {
		if ( empty( $hook['component'] ) ) {
			return \remove_filter( $hook['hook'], $hook['callback'], $hook['priority'] );
		} else {
			return \remove_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'] );
		}
	}
	/**
	 * Invokes a given function across every instance of filters and actions that we have.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $array_walk_function    The inner class method to call on each hook.
	 */
	protected function array_walk_hooks( string $array_walk_function ) : void {
		\array_walk( $this->filters, array( $this, $array_walk_function ) );
		\array_walk( $this->actions, array( $this, $array_walk_function ) );
	}
	// endregion
}
