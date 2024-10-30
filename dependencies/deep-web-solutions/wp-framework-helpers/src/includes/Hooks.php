<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers;

\defined( 'ABSPATH' ) || exit;
/**
 * A collection of very useful WP hooks helpers to be used throughout the projects.
 *
 * @since   1.0.0
 * @version 1.4.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers
 */
final class Hooks {

	// region METHODS
	/**
	 * Removes an anonymous object action or filter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string   $hook       Hook name.
	 * @param   string   $class      Class name.
	 * @param   string   $method     Method name.
	 */
	public static function remove_anonymous_object_hook( string $hook, string $class, string $method ) : void {
		$filters = $GLOBALS['wp_filter'][ $hook ];
		if ( empty( $filters ) ) {
			return;
		}
		foreach ( $filters as $priority => $filter ) {
			foreach ( $filter as $function ) {
				if ( \is_array( $function ) && \is_array( $function['function'] ) && \is_a( $function['function'][0], $class ) && $method === $function['function'][1] ) {
					\remove_filter( $hook, array( $function['function'][0], $method ), $priority );
				}
			}
		}
	}
	/**
	 * Returns the current priority of the hook currently executing.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  int
	 */
	public static function get_current_hook_priority() : int {
		return $GLOBALS['wp_filter'][ \current_filter() ]->current_priority();
	}
	/**
	 * Enqueues a callable to run on a given hook and priority, and deques the callable immediately after that.
	 *
	 * @since   1.0.0
	 * @version 1.4.0
	 *
	 * @param   string      $hook               Hook to enqueue callable on.
	 * @param   callable    $func               Callable to enqueue.
	 * @param   int         $priority           The priority to enqueue on.
	 * @param   int         $accepted_args      The number of accepted arguments of the callable.
	 *
	 * @return  bool
	 */
	public static function enqueue_temp( string $hook, callable $func, int $priority = 10, int $accepted_args = 1 ) : bool {
		return self::enqueue( $hook, $func, $priority, $accepted_args ) && self::enqueue( $hook, self::generate_dequeue_callable( $hook, $func, $priority ), $priority );
	}
	/**
	 * Enqueues a callable to run on the current hook's next priority, basically simulating a "tick".
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   callable    $func           Function to enqueue.
	 * @param   int         $accepted_args  The number of arguments the function accepts. Default 1.
	 *
	 * @return  int|null     The priority of the enqueued function or null on failure.
	 */
	public static function enqueue_on_next_tick( callable $func, int $accepted_args = 1 ) : ?int {
		$current_priority = self::get_current_hook_priority();
		if ( \PHP_INT_MAX !== $current_priority ) {
			return self::enqueue( \current_action(), $func, $current_priority + 1, $accepted_args ) ? $current_priority + 1 : null;
		}
		return null;
	}
	/**
	 * Enqueues a callable to run on the current hook's next priority, basically simulating a "tick", AND very importantly
	 * dequeues the callable on the "tick" after that.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   callable    $func           Function to enqueue.
	 * @param   int         $accepted_args  The number of arguments the function accepts. Default 1.
	 *
	 * @return  int|null    The priority of the enqueued function or null on failure.
	 */
	public static function enqueue_temp_on_next_tick( callable $func, int $accepted_args = 1 ) : ?int {
		$hooked_priority = self::enqueue_on_next_tick( $func, $accepted_args );
		return ! \is_null( $hooked_priority ) ? self::enqueue_on_next_tick( self::generate_dequeue_callable( \current_action(), $func, $hooked_priority ) ) : null;
	}
	// endregion
	// region HELPERS
	/**
	 * Wrapper around WordPress' own 'add_action' function just for the sake of refactoring the other methods in this class.
	 *
	 * @param   string      $hook               Hook to enqueue callable on.
	 * @param   callable    $func               Callable to enqueue.
	 * @param   int         $priority           The priority to enqueue on.
	 * @param   int         $accepted_args      The number of accepted arguments of the callable.
	 *
	 * @return  bool
	 */
	protected static function enqueue( string $hook, callable $func, int $priority = 10, int $accepted_args = 1 ) : bool {
		return \add_action( $hook, $func, $priority, $accepted_args );
	}
	/**
	 * Generates a callable that removes another callable from a WP hook.
	 *
	 * @param   string      $hook       Hook on which the callable is enqueued.
	 * @param   callable    $func       Callable to dequeue.
	 * @param   int         $priority   The priority on which the callable is hooked.
	 *
	 * @noinspection PhpInconsistentReturnPointsInspection
	 *
	 * @return  callable
	 */
	protected static function generate_dequeue_callable( string $hook, callable $func, int $priority ) : callable {
		return static function () use ( $hook, $func, $priority ) {
			\remove_action( $hook, $func, $priority );
			if ( ! empty( \func_get_args() ) && \doing_filter() ) {
				return \func_get_arg( 0 );
			}
		};
	}
	// endregion
}
