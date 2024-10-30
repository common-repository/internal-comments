<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes;

\defined( 'ABSPATH' ) || exit;
/**
 * A collection of useful string manipulation helpers to be used throughout the projects.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 *
 * @since   1.0.0
 * @version 1.7.1
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\DataTypes
 */
final class Strings {

	// region POLYFILLS
	/**
	 * Checks whether a string starts in a particular way or not.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @param   string  $haystack   String to check.
	 * @param   string  $needle     Beginning to check against.
	 *
	 * @return  bool    True if the string starts as expected, false otherwise.
	 */
	public static function starts_with( string $haystack, string $needle ) : bool {
		if ( \PHP_VERSION_ID >= 80000 && \function_exists( '\\str_starts_with' ) ) {
			\str_starts_with( $haystack, $needle );
		}
		return 0 === \strncmp( $haystack, $needle, \strlen( $needle ) );
	}
	/**
	 * Checks whether a string end in a particular way or not.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @param   string  $haystack   String to check.
	 * @param   string  $needle     Ending to check against.
	 *
	 * @return  bool    True if the string ends as expected, false otherwise.
	 */
	public static function ends_with( string $haystack, string $needle ) : bool {
		if ( \PHP_VERSION_ID >= 80000 && \function_exists( '\\str_ends_with' ) ) {
			return \str_ends_with( $haystack, $needle );
		}
		return '' === $needle || '' !== $haystack && 0 === \substr_compare( $haystack, $needle, -\strlen( $needle ) );
	}
	/**
	 * Checks whether a given string contains another smaller string or not.
	 *
	 * @since   1.7.0
	 * @version 1.7.0
	 *
	 * @param   string  $haystack   String to check.
	 * @param   string  $needle     Substring to search for.
	 *
	 * @return  bool
	 */
	public static function contains( string $haystack, string $needle ) : bool {
		if ( \PHP_VERSION_ID >= 80000 && \function_exists( '\\str_contains' ) ) {
			return \str_contains( $haystack, $needle );
		}
		return '' === $needle || \false !== \strpos( $haystack, $needle );
	}
	// endregion
	/**
	 * Returns a given variable if it is a string or a default value if not.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   mixed           $string     Variable to check.
	 * @param   string|null     $default    The default value to return if check fails. By default null.
	 *
	 * @return  string|null
	 */
	public static function validate( $string, ?string $default = null ) : ?string {
		return \is_string( $string ) ? $string : $default;
	}
	/**
	 * Attempts to turn a variable of unknown type into a string.
	 *
	 * @since   1.3.1
	 * @since   1.4.0   Moved to the Strings class.
	 * @version 1.5.4
	 *
	 * @param   mixed           $string     Variable to cast.
	 * @param   string|null     $default    The default value to return if all fails.
	 *
	 * @return  string|null
	 */
	public static function maybe_cast( $string, ?string $default = null ) : ?string {
		if ( \is_null( $string ) ) {
			return $default;
		}
		if ( ! \is_null( self::validate( $string ) ) ) {
			return $string;
		}
		if ( \is_null( Arrays::validate( $string ) ) && ( \is_null( Objects::validate( $string ) ) || \method_exists( $string, '__toString' ) ) ) {
			return (string) $string;
		}
		return $default;
	}
	/**
	 * Attempts to cast a variable from an input stream into an integer.
	 *
	 * @since   1.3.1
	 * @since   1.4.0   Moved to the Strings class.
	 * @version 1.4.0
	 *
	 * @param   int         $input_type     One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
	 * @param   string      $variable_name  Name of a variable to get from the input stream.
	 * @param   string|null $default        The default value to return if all fails. By default null.
	 *
	 * @return  string|null
	 */
	public static function maybe_cast_input( int $input_type, string $variable_name, ?string $default = null ) : ?string {
		if ( \filter_has_var( $input_type, $variable_name ) ) {
			$string = \filter_input( $input_type, $variable_name, \FILTER_UNSAFE_RAW, \FILTER_REQUIRE_SCALAR );
			return self::maybe_cast( $string, $default );
		}
		return $default;
	}
	/**
	 * Attempts to resolve a potential callable to a string.
	 *
	 * @since   1.3.0
	 * @since   1.4.0   Moved to the Strings class.
	 * @version 1.4.0
	 *
	 * @param   mixed|callable  $string     Potential callable to resolve.
	 * @param   string|null     $default    Default value to return on failure. By default null.
	 * @param   array           $args       Arguments to pass on to the callable. By default none.
	 *
	 * @return  string|null
	 */
	public static function resolve( $string, ?string $default = null, array $args = array() ) : ?string {
		return self::maybe_cast( Callables::maybe_resolve( $string, $args ), $default );
	}
	/**
	 * Validates a string against a safe-list.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   string          $string     The string to validate.
	 * @param   string[]        $allowed    Array of allowed entries.
	 * @param   string|null     $default    The value to return if string is not in safelist.
	 *
	 * @return  string|null
	 */
	public static function validate_allowed( string $string, array $allowed, ?string $default = null ) : ?string {
		$is_allowed = \in_array( $string, $allowed, \true );
		if ( \false === $is_allowed ) {
			$string     = \trim( $string );
			$is_allowed = \in_array( $string, $allowed, \true );
		}
		return $is_allowed ? $string : $default;
	}
	/**
	 * Adds a given prefix to a given string if not already prefixed.
	 *
	 * @since   1.4.4
	 * @version 1.4.4
	 *
	 * @param   string  $string     String to prefix.
	 * @param   string  $prefix     Prefix to add if not existent.
	 *
	 * @return  string
	 */
	public static function maybe_prefix( string $string, string $prefix = '_' ) : string {
		return self::starts_with( $string, $prefix ) ? $string : $prefix . $string;
	}
	/**
	 * Removes a given prefix from a given string if present.
	 *
	 * @since   1.4.4
	 * @version 1.4.4
	 *
	 * @param   string  $string     String to un-prefix.
	 * @param   string  $prefix     Prefix to remove if it exists.
	 *
	 * @return  string
	 */
	public static function maybe_unprefix( string $string, string $prefix = '_' ) : string {
		return self::starts_with( $string, $prefix ) ? \substr( $string, \strlen( $prefix ) ) : $string;
	}
	/**
	 * Adds a given suffix to a given string if not already suffixed.
	 *
	 * @since   1.4.4
	 * @version 1.4.4
	 *
	 * @param   string  $string     String to prefix.
	 * @param   string  $suffix     Suffix to add if not existent.
	 *
	 * @return  string
	 */
	public static function maybe_suffix( string $string, string $suffix ) : string {
		return self::ends_with( $string, $suffix ) ? $string : $string . $suffix;
	}
	/**
	 * Removes a given suffix from a given string if present.
	 *
	 * @since   1.4.4
	 * @version 1.4.4
	 *
	 * @param   string  $string     String to un-prefix.
	 * @param   string  $suffix     Suffix to remove if it exists.
	 *
	 * @return  string
	 */
	public static function maybe_unsuffix( string $string, string $suffix ) : string {
		return self::ends_with( $string, $suffix ) ? \substr( $string, 0, -1 * \strlen( $suffix ) ) : $string;
	}
	/**
	 * Takes an associate array($placeholder -> $replacement) and replaces all instances of $placeholder with $replacement
	 * inside the given string parameter.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @param   string  $string         The string containing the placeholders.
	 * @param   array   $placeholders   The values with which the placeholders must be replaced: {placeholder} => {value}.
	 *
	 * @return  string  Processed string with all the placeholders replaced.
	 */
	public static function replace_placeholders( string $string, array $placeholders ) : string {
		return \str_replace( \array_keys( $placeholders ), \array_values( $placeholders ), $string );
	}
	/**
	 * Transforms a string into a lowercase, safe version of itself.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 *
	 * @param   string  $string             String to transform.
	 * @param   array   $unsafe_characters  Unsafe characters and what to replace them with.
	 *
	 * @return  string
	 */
	public static function to_safe_string( string $string, array $unsafe_characters = array() ) : string {
		return \strtolower( self::to_ascii_input_string( self::replace_placeholders( $string, $unsafe_characters ) ) );
	}
	/**
	 * Transforms a string into an alphanumeric version of itself by removing any non-alphanumeric characters. Supports
	 * all unicode characters.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 *
	 * @see     https://stackoverflow.com/a/17151182
	 *
	 * @param   string  $string     The string to remove non-alphanumeric characters from.
	 *
	 * @return  string
	 */
	public static function to_alphanumeric_unicode_string( string $string ) : string {
		return \preg_replace( '/[^[:alnum:][:space:]]/u', '', $string );
	}
	/**
	 * Removes all non-ASCII characters and all non-alphanumeric ASCII characters from a string and returns the result.
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 *
	 * @see     https://stackoverflow.com/a/17151182
	 *
	 * @param   string  $string     The string to remove non-alphanumeric characters from.
	 *
	 * @return  string
	 */
	public static function to_alphanumeric_ascii_string( string $string ) : string {
		return \preg_replace( '/[^A-Za-z0-9 ]/', '', $string );
	}
	/**
	 * Removes all non-ASCII characters and all non-user-input ASCII characters (like null bytes and control characters)
	 * and returns the result.
	 *
	 * @since   1.1.0
	 * @version 1.7.1
	 *
	 * @param   string  $string     The string to remove the characters from.
	 *
	 * @return  string
	 */
	public static function to_ascii_input_string( string $string ) : string {
		return \filter_var( $string, \FILTER_UNSAFE_RAW, \FILTER_FLAG_STRIP_LOW | \FILTER_FLAG_STRIP_HIGH );
	}
	/**
	 * Transforms the php.ini notation for numbers (like 2M) to an integer.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @see     http://hookr.io/functions/wc_let_to_num/
	 *
	 * @noinspection PhpMissingBreakStatementInspection
	 *
	 * @param   string  $size   The php.ini size to transform into an integer.
	 *
	 * @return  int
	 */
	public static function letter_to_number( string $size ) : int {
		$letter = \substr( $size, -1 );
		$return = \substr( $size, 0, -1 );
        // phpcs:disable PSR2.ControlStructures.SwitchDeclaration.TerminatingComment
		switch ( \strtoupper( $letter ) ) {
			case 'P':
				$return *= 1024;
			case 'T':
				$return *= 1024;
			case 'G':
				$return *= 1024;
			case 'M':
				$return *= 1024;
			case 'K':
				$return *= 1024;
		}
        // phpcs:enable
		return $return;
	}
}
