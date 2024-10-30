<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotFoundException;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes the compatibility layer between the framework and a caching provider.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Caching
 */
interface CachingAdapterInterface {

	/**
	 * Returns a cached value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $key    The name of the cached value.
	 *
	 * @return  mixed|NotFoundException
	 */
	public function get_value( string $key);
	/**
	 * Returns multiple cached values.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $keys   The names of the cached values.
	 *
	 * @return  array
	 */
	public function get_value_multiple( array $keys) : array;
	/**
	 * Sets a cached value under a given name.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The name of the cached value.
	 * @param   mixed   $value  The data to cache.
	 * @param   int     $expire When the data should expire. Default never.
	 *
	 * @return  bool
	 */
	public function set_value( string $key, $value, int $expire = 0) : bool;
	/**
	 * Deletes a cached value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The name of the cached value.
	 *
	 * @return  bool
	 */
	public function delete_value( string $key) : bool;
	/**
	 * Deletes all the cached values.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function delete_all_values() : bool;
}
