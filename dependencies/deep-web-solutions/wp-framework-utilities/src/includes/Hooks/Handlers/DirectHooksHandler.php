<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\AbstractHooksHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Compatibility layer between the framework and WordPress' API for filters and actions. Maintains an internal list of
 * hooks and actions registered.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks\Handlers
 */
class DirectHooksHandler extends AbstractHooksHandler {

	// region MAGIC METHODS
	/**
	 * DirectHooksHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $handler_id     The ID of the handler instance.
	 */
	public function __construct( string $handler_id = 'direct' ) {
        // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		parent::__construct( $handler_id );
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function add_action( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ) : void {
		parent::add_action( $hook, $component, $callback, $priority, $accepted_args );
		if ( empty( $component ) ) {
			\add_action( $hook, $callback, $priority, $accepted_args );
		} else {
			\add_action( $hook, array( $component, $callback ), $priority, $accepted_args );
		}
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_action( string $hook, ?object $component, string $callback, int $priority = 10 ) : void {
		parent::remove_action( $hook, $component, $callback, $priority );
		if ( empty( $component ) ) {
			\remove_action( $hook, $callback, $priority );
		} else {
			\remove_action( $hook, array( $component, $callback, $priority ) );
		}
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_all_actions() : void {
		foreach ( $this->actions as $action ) {
			$this->remove_action( $action['hook'], $action['component'], $action['callback'], $action['priority'] );
		}
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function add_filter( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ) : void {
		parent::add_filter( $hook, $component, $callback, $priority, $accepted_args );
		if ( empty( $component ) ) {
			\add_filter( $hook, $callback, $priority, $accepted_args );
		} else {
			\add_filter( $hook, array( $component, $callback ), $priority, $accepted_args );
		}
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_filter( string $hook, ?object $component, string $callback, int $priority = 10 ) : void {
		parent::remove_filter( $hook, $component, $callback, $priority );
		if ( empty( $component ) ) {
			\remove_filter( $hook, $callback, $priority );
		} else {
			\remove_filter( $hook, array( $component, $callback, $priority ) );
		}
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_all_filters() : void {
		foreach ( $this->filters as $filter ) {
			$this->remove_filter( $filter['hook'], $filter['component'], $filter['callback'], $filter['priority'] );
		}
	}
	// endregion
}
