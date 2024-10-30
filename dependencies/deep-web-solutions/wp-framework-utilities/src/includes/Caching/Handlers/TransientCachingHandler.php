<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotFoundException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Integers;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching\AbstractCachingHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Compatibility layer between the framework and the WP Transient Cache.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Caching\Handlers
 */
class TransientCachingHandler extends AbstractCachingHandler {

	// region FIELDS AND CONSTANTS
	/**
	 * The prefix that should be added to all the data.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string|null
	 */
	protected ?string $prefix = null;
	// endregion
	// region MAGIC METHODS
	/**
	 * TransientCachingHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $handler_id     The default ID of the handler.
	 * @param   string|null     $prefix         The prefix for all values. Defaults to the plugin's safe slug.
	 */
	public function __construct( string $handler_id = 'transient', ?string $prefix = null ) {
		parent::__construct( $handler_id );
		$this->prefix = $prefix;
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
	public function get_keys_prefix() : string {
		return $this->prefix ?: $this->get_plugin()->get_plugin_safe_slug();
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
		$suffix     = \get_option( $suffix_key, \false );
		if ( \false === $suffix ) {
			\update_option( $suffix_key, 1, \true );
		}
		return Integers::maybe_cast( $suffix, 1 );
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
		return "{$this->get_keys_prefix()}_invalidation_suffix_key";
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_value( string $key ) {
		$key   = $this->generate_full_key( $key );
		$value = \get_transient( $key );
		return \false === $value ? new NotFoundException( $key ) : $value;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_value_multiple( array $keys ) : array {
		$values = array();
		foreach ( $keys as $key ) {
			$values[ $key ] = $this->get_value( $key );
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
		return \set_transient( $this->generate_full_key( $key ), $value, $expire );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_value( string $key ) : bool {
		return \delete_transient( $this->generate_full_key( $key ) );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_all_values() : bool {
		foreach ( $this->get_all_keys() as $full_key ) {
			// If stored in the database, this will delete them.
			// If stored in an external cache, incrementing the suffix will let the garbage collector deal with them.
			\delete_transient( $full_key );
		}
		return \update_option( $this->get_keys_suffix_key(), $this->get_keys_suffix() + 1, \true );
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
		return "{$this->get_keys_prefix()}/{$user_key}__{$this->get_keys_suffix()}";
	}
	/**
	 * Returns all the transient names from the database of transients that have been inserted by this handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @see     https://kellenmace.com/delete-transients-with-prefix-in-wordpress/
	 *
	 * @return  array
	 */
	protected function get_all_keys() : array {
		global $wpdb;
		$prefix = $wpdb->esc_like( '_transient_' . $this->get_keys_prefix() . '/' );
		$sql    = "SELECT `option_name` FROM {$wpdb->options} WHERE `option_name` LIKE '%s'";
		$keys   = $wpdb->get_results( $wpdb->prepare( $sql, $prefix . '%' ), ARRAY_A );
        // phpcs:ignore
        $keys = \is_wp_error($keys) ? array() : $keys;
		// Remove '_transient_' from the option name.
		return \array_map( fn( array $key) => \ltrim( $key['option_name'], '_transient_' ), $keys );
	}
	// endregion
}
