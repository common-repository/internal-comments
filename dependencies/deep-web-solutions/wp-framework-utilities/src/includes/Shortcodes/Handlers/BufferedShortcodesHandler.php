<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable\ResetFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable\ResetLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\ResettableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes\AbstractShortcodesHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Compatibility layer between the framework and WordPress' API for shortcodes.
 *
 * Maintain a list of all shortcodes that are registered throughout the plugin, and handles their registration with
 * the WordPress API after calling the run function.
 *
 * @see     https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/blob/master/plugin-name/includes/class-plugin-name-loader.php
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Shortcodes
 */
class BufferedShortcodesHandler extends AbstractShortcodesHandler implements RunnableInterface, ResettableInterface {

	// region TRAITS
	use RunLocalTrait;
	use ResetLocalTrait;
	// endregion
	// region MAGIC METHODS
	/**
	 * BufferedShortcodesHandler constructor.
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
	 * Register the shortcodes with WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  RunFailureException|null
	 */
	protected function run_local() : ?RunFailureException {
		foreach ( $this->shortcodes as $hook ) {
			if ( empty( $hook['component'] ) ) {
				\add_shortcode( $hook['tag'], $hook['callback'] );
			} else {
				\add_shortcode( $hook['tag'], array( $hook['component'], $hook['callback'] ) );
			}
		}
		return null;
	}
	/**
	 * Un-registers the shortcodes with WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ResetFailureException|null
	 */
	protected function reset_local() : ?ResetFailureException {
		foreach ( $this->shortcodes as $shortcode ) {
			\remove_shortcode( $shortcode );
		}
		return null;
	}
	// endregion
}
