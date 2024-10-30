<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\ResettableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractMultiHandlerService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\Actions\ResetHandlersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\Actions\RunHandlersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes\Handlers\BufferedShortcodesHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Compatibility layer between the framework and WordPress' API for shortcodes.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Shortcodes
 */
class ShortcodesService extends AbstractMultiHandlerService implements ShortcodesServiceInterface, HooksServiceRegisterInterface, RunnableInterface, ResettableInterface {

	// region TRAITS
	use HooksServiceRegisterTrait;
	use ResetHandlersTrait;
	use RunHandlersTrait;
	// endregion
	// region MAGIC METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function __construct( PluginInterface $plugin, LoggingService $logging_service, HooksService $hooks_service, array $handlers = array() ) {
		parent::__construct( $plugin, $logging_service, $handlers );
		$this->register_hooks( $hooks_service );
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_handler( string $handler_id ) : ShortcodesHandlerInterface {
        // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_handler( $handler_id );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_hooks( HooksService $hooks_service ) : void {
		$hooks_service->add_action( 'init', $this, 'run', 10, 0 );
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function add_shortcode( string $tag, ?object $component, string $callback, string $handler_id = 'buffered' ) : void {
		$this->get_handler( $handler_id )->add_shortcode( $tag, $component, $callback );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_shortcode( string $tag, ?object $component, string $callback, string $handler_id = 'buffered' ) : void {
		$this->get_handler( $handler_id )->remove_shortcode( $tag, $component, $callback );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_all_shortcodes( string $handler_id = 'buffered' ) : void {
		$this->get_handler( $handler_id )->remove_all_shortcodes();
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
		return array( BufferedShortcodesHandler::class );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_handler_class() : string {
		return ShortcodesHandlerInterface::class;
	}
	// endregion
}
