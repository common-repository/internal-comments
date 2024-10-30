<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes;

\defined( 'ABSPATH' ) || exit;
/**
 * A collection of useful integer helpers to be used throughout the projects.
 *
 * @since   1.4.0
 * @version 1.4.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\DataTypes
 */
final class Integers {

	/**
	 * Returns a given variable if it is an integer or a default value if not.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   mixed       $integer    Variable to check.
	 * @param   int|null    $default    The default value to return if check fails. By default null.
	 *
	 * @return  int|null
	 */
	public static function validate( $integer, ?int $default = null ) : ?int {
		return \is_int( $integer ) ? $integer : $default;
	}
	/**
	 * Attempts to cast a variable of unknown type into an integer.
	 *
	 * @since   1.0.0
	 * @since   1.4.0   Moved to the Integers class.
	 * @version 1.4.0
	 *
	 * @param   mixed       $integer    Variable to cast.
	 * @param   int|null    $default    The default value to return if all fails. By default null.
	 *
	 * @return  int|null
	 */
	public static function maybe_cast( $integer, ?int $default = null ) : ?int {
		$integer = \filter_var( $integer, \FILTER_VALIDATE_INT, \FILTER_FLAG_ALLOW_OCTAL | \FILTER_FLAG_ALLOW_HEX );
		return self::validate( $integer, $default );
	}
	/**
	 * Attempts to cast a variable from an input stream into an integer.
	 *
	 * @since   1.0.0
	 * @since   1.4.0   Moved to the Integers class.
	 * @version 1.4.0
	 *
	 * @param   int         $input_type     One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
	 * @param   string      $variable_name  Name of a variable to get from the input stream.
	 * @param   int|null    $default        The default value to return if all fails. By default null.
	 *
	 * @return  int|null
	 */
	public static function maybe_cast_input( int $input_type, string $variable_name, ?int $default = null ) : ?int {
		if ( \filter_has_var( $input_type, $variable_name ) ) {
			$integer = \filter_input( $input_type, $variable_name, \FILTER_UNSAFE_RAW, \FILTER_REQUIRE_SCALAR );
			return self::maybe_cast( $integer, $default );
		}
		return $default;
	}
	/**
	 * Attempts to resolve a potential callable to an integer.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   mixed|callable  $integer    Potential callable to resolve.
	 * @param   int|null        $default    Default value to return on failure. By default null.
	 * @param   array           $args       Arguments to pass on to the callable. By default none.
	 *
	 * @return  int|null
	 */
	public static function resolve( $integer, ?int $default = null, array $args = array() ) : ?int {
		return self::maybe_cast( Callables::maybe_resolve( $integer, $args ), $default );
	}
}
