<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractMultiHandlerService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching\Handlers\ObjectCacheHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching\Handlers\TransientCachingHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Compatibility layer between the framework and WordPress' API for caching.
 *
 * @see     https://core.trac.wordpress.org/ticket/4476#comment:10
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Caching
 */
class CachingService extends AbstractMultiHandlerService implements CachingServiceInterface {

	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_handler( string $handler_id ) : ?CachingHandlerInterface {
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
	public function get_value( string $key, string $handler_id = 'object' ) {
		return $this->get_handler( $handler_id )->get_value( $key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function get_value_multiple( array $keys, string $handler_id = 'object' ) : array {
		return $this->get_handler( $handler_id )->get_value_multiple( $keys );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function set_value( string $key, $value, int $expire = 0, string $handler_id = 'object' ) : bool {
		return $this->get_handler( $handler_id )->set_value( $key, $value, $expire );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function delete_value( string $key, string $handler_id = 'object' ) : bool {
		return $this->get_handler( $handler_id )->delete_value( $key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function delete_all_values( string $handler_id = 'object' ) : bool {
		return $this->get_handler( $handler_id )->delete_all_values();
	}
	// endregion
	// region HELPERS
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	protected function get_default_handlers_classes() : array {
		return array( ObjectCacheHandler::class, TransientCachingHandler::class );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	protected function get_handler_class() : string {
		return CachingHandlerInterface::class;
	}
	// endregion
}
