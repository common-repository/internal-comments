<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializationFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializeLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\InitializableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable\ResetFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunFailureException;
\defined( 'ABSPATH' ) || exit;
/**
 * Modified version of the Hooks default handler that differs by keeping the hooks registered only within a certain scope
 * delimited by certain start and end hooks, respectively.
 *
 * @see     https://github.com/andykeith/barn2-lib/blob/master/lib/class-wp-scoped-hooks.php
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks\Handlers
 */
class ScopedHooksHandler extends BufferedHooksHandler implements InitializableInterface {

	// region TRAITS
	use InitializeLocalTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * The hook on which the actions and filters should be registered.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     array   $start
	 */
	protected array $start;
	/**
	 * The hook on which the actions and filters should be un-registered.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     array   $end
	 */
	protected array $end;
	// endregion
	// region MAGIC METHODS
	/**
	 * ScopedHooksHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler_id     The ID of the handler instance.
	 * @param   array   $start          The hook on which the actions and filters should be registered.
	 * @param   array   $end            The hook on which the actions and filters should be un-registered.
	 */
	public function __construct( string $handler_id, array $start = array(), array $end = array() ) {
		parent::__construct( $handler_id );
		$this->parse_scope( $start, $end );
		$this->initialize();
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * Initialize the filters and actions collections and maybe hooks the instances run and reset methods.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  InitializationFailureException|null
	 */
	protected function initialize_local() : ?InitializationFailureException {
		$this->remove_all_actions();
		$this->remove_all_filters();
		if ( \is_string( $this->start['hook'] ) && ! empty( $this->start['hook'] ) ) {
			$this->array_walk_add_hook( $this->start );
		}
		if ( \is_string( $this->end['hook'] ) && ! empty( $this->end['hook'] ) ) {
			$this->array_walk_add_hook( $this->end );
		}
		return null;
	}
	/**
	 * Perform the registered hooks manipulation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  RunFailureException|null
	 */
	protected function run_local() : ?RunFailureException {
		\array_walk( $this->filters['added'], array( $this, 'array_walk_add_hook' ) );
		$this->filters['removed'] = \array_filter( $this->filters['removed'], array( $this, 'array_walk_remove_hook' ) );
		\array_walk( $this->actions['added'], array( $this, 'array_walk_add_hook' ) );
		$this->actions['removed'] = \array_filter( $this->actions['removed'], array( $this, 'array_walk_remove_hook' ) );
		return null;
	}
	/**
	 * Undo the registered hooks manipulation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ResetFailureException|null
	 */
	protected function reset_local() : ?ResetFailureException {
		\array_walk( $this->filters['added'], array( $this, 'array_walk_remove_hook' ) );
		\array_walk( $this->filters['removed'], array( $this, 'array_walk_add_hook' ) );
		\array_walk( $this->actions['added'], array( $this, 'array_walk_remove_hook' ) );
		\array_walk( $this->actions['removed'], array( $this, 'array_walk_add_hook' ) );
		return null;
	}
	// endregion
	// region METHODS
	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress action that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the action is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int            $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ) : void {
		$this->actions['added'] = $this->add( $this->actions['added'], $hook, $component, $callback, $priority, $accepted_args );
	}
	/**
	 * Remove an action from the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress action that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the action is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 */
	public function remove_added_action( string $hook, ?object $component, string $callback, int $priority = 10 ) : void {
		$this->actions['added'] = $this->remove( $this->actions['added'], $hook, $component, $callback, $priority );
	}
	/**
	 * Add a new action to the collection to be unregistered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress action that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the action is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int            $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function remove_action( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ) : void {
		$this->actions['removed'] = $this->add( $this->actions['removed'], $hook, $component, $callback, $priority, $accepted_args );
	}
	/**
	 * Remove an action from the collection to be unregistered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress action that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the action is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 */
	public function remove_removed_action( string $hook, ?object $component, string $callback, int $priority = 10 ) : void {
		$this->actions['removed'] = $this->remove( $this->actions['removed'], $hook, $component, $callback, $priority );
	}
	/**
	 * Reinitialize the actions collection.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_all_actions() : void {
		$this->actions = array(
			'added'   => array(),
			'removed' => array(),
		);
	}
	/**
	 * Add a new filter to the collection to be unregistered with WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $hook           The name of the WordPress filter that is being registered.
	 * @param   object|null     $component      A reference to the instance of the object on which the filter is defined.
	 * @param   string          $callback       The name of the function definition on the $component.
	 * @param   int             $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param   int             $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_filter( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ) : void {
		$this->filters['added'] = $this->add( $this->filters['added'], $hook, $component, $callback, $priority, $accepted_args );
	}
	/**
	 * Remove a filter from the collection to be registered with WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $hook           The name of the WordPress filter that is being registered.
	 * @param   object|null     $component      A reference to the instance of the object on which the filter is defined.
	 * @param   string          $callback       The name of the function definition on the $component.
	 * @param   int             $priority       Optional. he priority at which the function should be fired. Default is 10.
	 */
	public function remove_added_filter( string $hook, ?object $component, string $callback, int $priority = 10 ) : void {
		$this->filters['added'] = $this->remove( $this->filters['added'], $hook, $component, $callback, $priority );
	}
	/**
	 * Add a new filter to the collection to be unregistered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress filter that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the filter is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int            $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function remove_filter( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ) : void {
		$this->filters['removed'] = $this->add( $this->filters['removed'], $hook, $component, $callback, $priority, $accepted_args );
	}
	/**
	 * Remove a filter to the collection to be unregistered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress filter that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the filter is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 */
	public function remove_removed_filter( string $hook, ?object $component, string $callback, int $priority = 10 ) : void {
		$this->filters['removed'] = $this->remove( $this->filters['removed'], $hook, $component, $callback, $priority );
	}
	/**
	 * Reinitialize the filters collection.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_all_filters() : void {
		$this->filters = array(
			'added'   => array(),
			'removed' => array(),
		);
	}
	// endregion
	// region HELPERS
	/**
	 * Parses the start and end hooks parameters.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $start  The hook on which the actions and filters should be registered.
	 * @param   array   $end    The hook on which the actions and filters should be un-registered.
	 */
	protected function parse_scope( array $start, array $end ) : void {
		$this->start = \array_merge(
			\wp_parse_args( $start, $this->get_scope_hook_defaults() ),
			array(
				'component'     => $this,
				'callback'      => 'run',
				'accepted_args' => 0,
			)
		);
		$this->end   = \array_merge(
			\wp_parse_args( $end, $this->get_scope_hook_defaults() ),
			array(
				'component'     => $this,
				'callback'      => 'reset',
				'accepted_args' => 0,
			)
		);
	}
	/**
	 * Gets a default scope hook configuration.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	protected function get_scope_hook_defaults() : array {
		return array(
			'hook'     => '',
			'type'     => 'action',
			'priority' => 10,
		);
	}
	// endregion
}
