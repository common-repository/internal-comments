<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotFoundException;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the caching-service-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Caching
 */
trait CachingServiceAwareTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Caching service instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     CachingService
	 */
	protected CachingService $caching_service;
	// endregion
	// region GETTERS
	/**
	 * Gets the current caching service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  CachingService
	 */
	public function get_caching_service() : CachingService {
		return $this->caching_service;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets a caching service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   CachingService       $service        Caching service instance to use from now on.
	 */
	public function set_caching_service( CachingService $service ) {
		$this->caching_service = $service;
	}
	// endregion
	// region METHODS
	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $key            The key under which the cache contents are stored.
	 * @param   string      $handler_id     ID of the handler to return the value from.
	 *
	 * @return  mixed|NotFoundException
	 */
	protected function get_cache_value( string $key, string $handler_id = 'object' ) {
		return $this->get_caching_service()->get_value( $key, $handler_id );
	}
	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key            The cache key to use for retrieval later.
	 * @param   mixed   $data           The contents to store in the cache.
	 * @param   int     $expire         When to expire the cache contents, in seconds. Default 0 (no expiration).
	 * @param   string  $handler_id     ID of the handler to set the value with.
	 *
	 * @return  bool    True on success, false on failure.
	 */
	protected function set_cache_value( string $key, $data, int $expire = 0, string $handler_id = 'object' ) : bool {
		return $this->get_caching_service()->set_value( $key, $data, $expire, $handler_id );
	}
	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key            The name of the cached value.
	 * @param   string  $handler_id     ID of the handler to set the value from.
	 *
	 * @return  bool
	 */
	protected function delete_cache_value( string $key, string $handler_id = 'object' ) : bool {
		return $this->get_caching_service()->delete_value( $key, $handler_id );
	}
	// endregion
}
