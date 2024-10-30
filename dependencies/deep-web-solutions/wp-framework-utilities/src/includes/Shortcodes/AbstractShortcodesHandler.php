<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating some of the most often needed functionality of a shortcodes handler.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Shortcodes
 */
abstract class AbstractShortcodesHandler extends AbstractHandler implements ShortcodesHandlerInterface {

	// region FIELDS AND CONSTANTS
	/**
	 * The shortcodes registered with WordPress that can be used after the service runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $shortcodes = array();
	// endregion
	// region GETTERS
	/**
	 * Returns the list of shortcodes registered with WP by this service instance on run.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	public function get_shortcodes() : array {
		return $this->shortcodes;
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_type() : string {
		return 'shortcodes';
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function add_shortcode( string $tag, ?object $component, string $callback ) : void {
		$this->shortcodes[] = array(
			'tag'       => $tag,
			'component' => $component,
			'callback'  => $callback,
		);
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_shortcode( string $tag, ?object $component, string $callback ) : void {
		foreach ( $this->shortcodes as $index => $hook_info ) {
			if ( $hook_info['tag'] === $tag && $hook_info['component'] === $component && $hook_info['callback'] === $callback ) {
				unset( $this->shortcodes[ $index ] );
				break;
			}
		}
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_all_shortcodes() : void {
		$this->shortcodes = array();
	}
	// endregion
}
