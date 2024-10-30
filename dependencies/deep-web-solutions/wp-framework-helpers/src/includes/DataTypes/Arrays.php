<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes;

\defined( 'ABSPATH' ) || exit;
/**
 * A collection of very useful array manipulation helpers to be used throughout the projects.
 *
 * @since   1.0.0
 * @version 1.5.6
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\DataTypes
 */
final class Arrays {

	// region POLYFILLS
	/**
	 * Checks whether a given array is a list or not. An array is considered a list if its keys consist of consecutive numbers from 0 to count($array)-1.
	 *
	 * @since   1.5.6
	 * @version 1.5.6
	 *
	 * @see     https://www.php.net/manual/en/function.array-is-list.php#126574
	 *
	 * @param   array   $array  The array to check.
	 *
	 * @return  bool    True if the array is a list, false otherwise.
	 */
	public static function is_list( array $array ) : bool {
		if ( \PHP_VERSION_ID >= 80100 && \function_exists( '\array_is_list' ) ) {
			return \array_is_list( $array );
		}
		return array() === $array || \array_keys( $array ) === \range( 0, \count( $array ) - 1 );
	}
	// endregion
	/**
	 * Returns a given variable if it is an array or a default value if not.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   mixed       $array      Variable to check.
	 * @param   array|null  $default    The default value to return if check fails. By default, null.
	 *
	 * @return  array|null
	 */
	public static function validate( $array, ?array $default = null ) : ?array {
		return \is_array( $array ) ? $array : $default;
	}
	/**
	 * Attempts to turn a variable of unknown type into an array.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   mixed       $array      Variable to cast.
	 * @param   array|null  $default    The default value to return if all fails. By default, null.
	 *
	 * @return  array|null
	 */
	public static function maybe_cast( $array, ?array $default = null ) : ?array {
		if ( ! \is_null( self::validate( $array ) ) ) {
			return $array;
		}
		if ( ! \is_null( $array ) ) {
			return array( $array );
		}
		return $default;
	}
	/**
	 * Attempts to cast a variable from an input stream into an array.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   int         $input_type     One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
	 * @param   string      $variable_name  Name of a variable to get.
	 * @param   array|null  $default        The default value to return if all fails.
	 *
	 * @return  array|null
	 */
	public static function maybe_cast_input( int $input_type, string $variable_name, ?array $default = null ) : ?array {
		if ( \filter_has_var( $input_type, $variable_name ) ) {
			$array = \filter_input( $input_type, $variable_name, \FILTER_UNSAFE_RAW, \FILTER_FORCE_ARRAY );
			return self::maybe_cast( $array, $default );
		}
		return $default;
	}
	/**
	 * Attempts to resolve a potential callable to an array value.
	 *
	 * @since   1.7.0
	 * @version 1.7.0
	 *
	 * @param   mixed|callable  $array      Potential callable to resolve.
	 * @param   array|null      $default    Default value to return on failure. By default, null.
	 * @param   array           $args       Arguments to pass on to the callable. By default, none.
	 *
	 * @return  array|null
	 */
	public static function resolve( $array, ?array $default = null, array $args = array() ) : ?array {
		return self::maybe_cast( Callables::maybe_resolve( $array, $args ), $default );
	}
	/**
	 * Validates an array of values against a safe-list. Returns a new array containing only valid entries.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   array       $entries    Array of entries to validate.
	 * @param   array       $allowed    Array of allowed entries.
	 * @param   mixed|null  $default    The value to replace invalid entries with.
	 *
	 * @return  array
	 */
	public static function validate_allowed( array $entries, array $allowed, $default = null ) : array {
		return \array_map(
			static function ( $entry ) use ( $allowed, $default ) {
				$is_allowed = \in_array( $entry, $allowed, \true );
				if ( \false === $is_allowed && Strings::validate( $entry ) ) {
					$entry      = \trim( $entry );
					$is_allowed = \in_array( $entry, $allowed, \true );
				}
				return $is_allowed ? $entry : $default;
			},
			$entries
		);
	}
	/**
	 * Checks whether an array has any string keys or if they are all numerical.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $array  The array to check.
	 *
	 * @return  bool    True if it has string keys, false otherwise.
	 */
	public static function has_string_keys( array $array ) : bool {
		return \count( \array_filter( \array_keys( $array ), '\\is_string' ) ) > 0;
	}
	/**
	 * Inserts new array entries after a given key within an existing array. If the key is not found,
	 * appends entries at the end of the array.
	 *
	 * @since   1.0.0
	 * @version 1.0.2
	 * @see     https://gist.github.com/wpscholar/0deadce1bbfa4adb4e4c
	 *
	 * @param   array           $array          Associative array to insert the new entries into.
	 * @param   string|int      $key            Key to insert the entries after.
	 * @param   array           $new_entries    The new entries to insert.
	 *
	 * @return  array
	 */
	public static function insert_after( array $array, $key, array $new_entries ) : array {
		$index    = \array_search( $key, \array_keys( $array ), \true );
		$position = \false === $index ? \count( $array ) : $index + 1;
		if ( empty( $array ) || self::has_string_keys( $array ) ) {
			$array = \array_slice( $array, 0, $position, \true ) + $new_entries + \array_slice( $array, $position, null, \true );
		} else {
			\array_splice( $array, $position, 0, $new_entries );
		}
		return $array;
	}
	/**
	 * Returns all the entries in a given array that match a given needle.
	 *
	 * @since   1.0.0
	 * @version 1.4.0
	 *
	 * @param   array           $array      Array to search through.
	 * @param   mixed           $needle     The value to search for.
	 * @param   callable|null   $callback   Optional callback to run the value through before needle comparison.
	 * @param   bool            $strict     Whether to perform type checks or not.
	 *
	 * @return  array|null
	 */
	public static function search_values( array $array, $needle, ?callable $callback = null, bool $strict = \true ) : ?array {
		$comparison_array = \is_callable( $callback ) ? \array_map( $callback, $array ) : $array;
		foreach ( $comparison_array as $key => $value ) {
			if ( $strict && $needle === $value || ! $strict && $needle == $value ) {
                // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				continue;
			}
			unset( $array[ $key ] );
		}
		return empty( $array ) ? null : $array;
	}
	/**
	 * Returns all the keys in a given array that match a given needle.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array           $array      Array whose keys to search through.
	 * @param   mixed           $needle     The value to search for.
	 * @param   callable|null   $callback   Optional callback to run the key through before needle comparison.
	 * @param   bool            $strict     Whether to perform type checks or not.
	 *
	 * @return  array|null
	 */
	public static function search_keys( array $array, $needle, ?callable $callback = null, bool $strict = \false ) : ?array {
		return self::search_values( \array_keys( $array ), $needle, $callback, $strict );
	}
}
