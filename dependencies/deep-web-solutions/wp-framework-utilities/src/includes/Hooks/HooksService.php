<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\ResettableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractMultiHandlerService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\Actions\ResetHandlersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\Actions\RunHandlersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Handlers\BufferedHooksHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Handlers\DirectHooksHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Compatibility layer between the framework and WordPress' API for hooks.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks
 */
class HooksService extends AbstractMultiHandlerService implements HooksServiceInterface, RunnableInterface, ResettableInterface {

	// region TRAITS
	use ResetHandlersTrait;
	use RunHandlersTrait;
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_handler( string $handler_id ) : ?HooksHandlerInterface {
        // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_handler( $handler_id );
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function add_action( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1, string $handler_id = 'buffered' ) : void {
		$this->get_handler( $handler_id )->add_action( $hook, $component, $callback, $priority, $accepted_args );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_action( string $hook, ?object $component, string $callback, int $priority = 10, string $handler_id = 'buffered' ) : void {
		$this->get_handler( $handler_id )->remove_action( $hook, $component, $callback, $priority );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_all_actions( string $handler_id = 'buffered' ) : void {
		$this->get_handler( $handler_id )->remove_all_actions();
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function add_filter( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1, string $handler_id = 'buffered' ) : void {
		$this->get_handler( $handler_id )->add_filter( $hook, $component, $callback, $priority, $accepted_args );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_filter( string $hook, ?object $component, string $callback, int $priority = 10, string $handler_id = 'buffered' ) : void {
		$this->get_handler( $handler_id )->remove_filter( $hook, $component, $callback, $priority );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_all_filters( string $handler_id = 'buffered' ) : void {
		$this->get_handler( $handler_id )->remove_all_filters();
	}
	// endregion
	// region HELPERS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_default_handlers_classes() : array {
		return array( BufferedHooksHandler::class, DirectHooksHandler::class );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_handler_class() : string {
		return HooksHandlerInterface::class;
	}
	// endregion
}
