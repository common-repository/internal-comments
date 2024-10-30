<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating some of the most often needed functionality of a hooks handler.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks
 */
abstract class AbstractHooksHandler extends AbstractHandler implements HooksHandlerInterface {

	// region FIELDS AND CONSTANTS
	/**
	 * The actions registered with WordPress to fire when the service runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $actions = array();
	/**
	 * The filters registered with WordPress to fire when the service runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $filters = array();
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_type() : string {
		return 'hooks';
	}
	// endregion
	// region GETTERS
	/**
	 * Returns the list of actions registered with WP by this handler instance on run.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	public function get_actions() : array {
		return $this->actions;
	}
	/**
	 * Returns the list of filters registered with WP by this handler instance on run.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	public function get_filters() : array {
		return $this->filters;
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function add_action( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ) : void {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_action( string $hook, ?object $component, string $callback, int $priority = 10 ) : void {
		$this->actions = $this->remove( $this->actions, $hook, $component, $callback, $priority );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_all_actions() : void {
		$this->actions = array();
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function add_filter( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ) : void {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_filter( string $hook, ?object $component, string $callback, int $priority = 10 ) : void {
		$this->filters = $this->remove( $this->filters, $hook, $component, $callback, $priority );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_all_filters() : void {
		$this->filters = array();
	}
	// endregion
	// region HELPERS
	/**
	 * A utility function that is used to register the actions and hooks into a single collection.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    array          $hooks          The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string         $hook           The name of the WordPress filter that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the filter is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       The priority at which the function should be fired.
	 * @param    int            $accepted_args  The number of arguments that should be passed to the $callback.
	 *
	 * @return   array      The collection of actions and filters registered with WordPress.
	 */
	protected function add( array $hooks, string $hook, ?object $component, string $callback, int $priority, int $accepted_args ) : array {
		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);
		return $hooks;
	}
	/**
	 * A utility function that is used to remove the actions and hooks from the single collection.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @access   protected
	 *
	 * @param    array          $hooks          The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string         $hook           The name of the WordPress filter that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the filter is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       The priority at which the function should be fired.
	 *
	 * @return   array      The collection of actions and filters registered with WordPress.
	 */
	protected function remove( array $hooks, string $hook, ?object $component, string $callback, int $priority ) : array {
		foreach ( $hooks as $index => $hook_info ) {
			if ( $hook_info['hook'] === $hook && $hook_info['component'] === $component && $hook_info['callback'] === $callback && $hook_info['priority'] === $priority ) {
				unset( $hooks[ $index ] );
				break;
			}
		}
		return $hooks;
	}
	// endregion
}
