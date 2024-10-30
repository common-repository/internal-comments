<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotFoundException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Integers;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching\AbstractCachingHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Compatibility layer between the framework and the WP Object Cache.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Caching\Handlers
 */
class ObjectCacheHandler extends AbstractCachingHandler {

	// region FIELDS AND CONSTANTS
	/**
	 * The caching group that the data should be added to.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string|null
	 */
	protected ?string $group = null;
	// endregion
	// region MAGIC METHODS
	/**
	 * ObjectCacheHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $handler_id     The default ID of the handler.
	 * @param   string|null     $group          The group to assign values to. Defaults to the plugin's safe slug.
	 */
	public function __construct( string $handler_id = 'object', ?string $group = null ) {
		parent::__construct( $handler_id );
		$this->group = $group;
	}
	// endregion
	// region GETTERS
	/**
	 * Returns the object cache group.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_cache_group() : string {
		return $this->group ?: $this->get_plugin()->get_plugin_safe_slug();
	}
	/**
	 * Returns the key of the cache entry holding the cache invalidation suffix.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_keys_suffix_key() : string {
		return "{$this->get_cache_group()}_invalidation_suffix_key";
	}
	/**
	 * Returns the cache invalidation suffix.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  int
	 */
	public function get_keys_suffix() : int {
		$suffix_key = $this->get_keys_suffix_key();
		$suffix     = \wp_cache_get( $suffix_key );
		if ( \false === $suffix ) {
			\wp_cache_set( $suffix_key, 1 );
		}
		return Integers::maybe_cast( $suffix, 1 );
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_value( string $key, bool $force = \false ) {
		$key   = $this->generate_full_key( $key );
		$found = null;
		$value = \wp_cache_get( $key, $this->get_cache_group(), $force, $found );
		return \false === $found ? new NotFoundException( $key ) : $value;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_value_multiple( array $keys, bool $force = \false ) : array {
		foreach ( $keys as $key => $value ) {
			$keys[ $this->generate_full_key( $value ) ] = $value;
			unset( $keys[ $key ] );
		}
		$values = \wp_cache_get_multiple( \array_keys( $keys ), $this->get_cache_group(), $force );
		foreach ( $values as $key => $value ) {
			$original_key            = $keys[ $key ];
			$values[ $original_key ] = $value;
			unset( $values[ $key ] );
		}
		return $values;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function set_value( string $key, $value, int $expire = 0 ) : bool {
		return \wp_cache_set( $this->generate_full_key( $key ), $value, $this->get_cache_group(), $expire );
	}
	/**
	 * Replaces the contents of the cache with new data.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string $key    The key for the cache data that should be replaced.
	 * @param   mixed  $value  The new data to store in the cache.
	 * @param   int    $expire When to expire the cache contents, in seconds. Default 0 (no expiration).
	 *
	 * @return  bool    False if original value does not exist, true if contents were replaced.
	 */
	public function replace_value( string $key, $value, int $expire = 0 ) : bool {
		return \wp_cache_replace( $this->generate_full_key( $key ), $value, $this->get_cache_group(), $expire );
	}
	/**
	 * Adds data to the cache, if the cache key doesn't already exist.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string $key    The key for the cache data that should be replaced.
	 * @param   mixed  $value  The new data to store in the cache.
	 * @param   int    $expire When to expire the cache contents, in seconds. Default 0 (no expiration).
	 *
	 * @return bool     True on success, false if cache key already exists.
	 */
	public function add_value( string $key, $value, int $expire = 0 ) : bool {
		return \wp_cache_add( $this->generate_full_key( $key ), $value, $this->get_cache_group(), $expire );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_value( string $key ) : bool {
		return \wp_cache_delete( $this->generate_full_key( $key ), $this->get_cache_group() );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_all_values() : bool {
		return ! ( \false === \wp_cache_incr( $this->get_keys_suffix_key() ) );
	}
	// endregion
	// region HELPERS
	/**
	 * Generates the final object cache key from the given user-land key.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $user_key   User-land key.
	 *
	 * @return  string
	 */
	protected function generate_full_key( string $user_key ) : string {
		return "{$user_key}__{$this->get_keys_suffix()}";
	}
	// endregion
}
