<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes;

\defined( 'ABSPATH' ) || exit;
/**
 * A collection of very useful boolean helpers to be used throughout the projects.
 *
 * @since   1.0.0
 * @version 1.4.4
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\DataTypes
 */
final class Booleans {

	/**
	 * Returns a given variable if it is a boolean or a default value if not.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   mixed       $boolean    Variable to check.
	 * @param   bool|null   $default    The default value to return if check fails. By default null.
	 *
	 * @return  bool|null
	 */
	public static function validate( $boolean, ?bool $default = null ) : ?bool {
		return \is_bool( $boolean ) ? $boolean : $default;
	}
	/**
	 * Attempts to cast a variable of unknown type into a boolean.
	 *
	 * @since   1.0.0
	 * @since   1.4.0   Moved to the Booleans class.
	 * @version 1.4.2
	 *
	 * @param   mixed       $boolean    Variable to cast.
	 * @param   bool|null   $default    The default value to return if all fails. By default null.
	 *
	 * @return  bool|null
	 */
	public static function maybe_cast( $boolean, ?bool $default = null ) : ?bool {
		if ( \is_null( $boolean ) ) {
			return $default;
		}
		$boolean = \filter_var( $boolean, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE );
		return self::validate( $boolean, $default );
	}
	/**
	 * Attempts to cast a variable from an input stream into a boolean value.
	 *
	 * @since   1.0.0
	 * @since   1.4.0   Moved to the Booleans class.
	 * @version 1.4.0
	 *
	 * @param   int         $input_type     One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
	 * @param   string      $variable_name  Name of a variable to get from the input stream.
	 * @param   bool|null   $default        The default value to return if all fails. By default null.
	 *
	 * @return  bool|null
	 */
	public static function maybe_cast_input( int $input_type, string $variable_name, ?bool $default = null ) : ?bool {
		if ( \filter_has_var( $input_type, $variable_name ) ) {
			$boolean = \filter_input( $input_type, $variable_name, \FILTER_UNSAFE_RAW, \FILTER_REQUIRE_SCALAR | \FILTER_NULL_ON_FAILURE );
			return self::maybe_cast( $boolean, $default );
		}
		return $default;
	}
	/**
	 * Attempts to resolve a potential callable to a boolean value.
	 *
	 * @since   1.3.0
	 * @since   1.4.0   Moved to the Booleans class.
	 * @version 1.4.0
	 *
	 * @param   mixed|callable  $bool       Potential callable to resolve.
	 * @param   bool|null       $default    Default value to return on failure. By default null.
	 * @param   array           $args       Arguments to pass on to the callable. By default none.
	 *
	 * @return  bool|null
	 */
	public static function resolve( $bool, ?bool $default = null, array $args = array() ) : ?bool {
		return self::maybe_cast( Callables::maybe_resolve( $bool, $args ), $default );
	}
	/**
	 * Useful class for calls to functional programming constructs, such as 'array_reduce'. Returns the logical or result
	 * of the two parameters.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   bool    $boolean1   The first boolean value.
	 * @param   bool    $boolean2   The second boolean value.
	 *
	 * @return  bool    The result of "or-ing" the two boolean parameters.
	 */
	public static function logical_or( bool $boolean1, bool $boolean2 ) : bool {
		return $boolean1 || $boolean2;
	}
	/**
	 * Useful class for calls to functional programming constructs, such as 'array_reduce'. Returns the logical or result
	 * of the two parameters.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   bool    $boolean1   The first boolean value.
	 * @param   bool    $boolean2   The second boolean value.
	 *
	 * @return  bool    The result of "and-ing" the two boolean parameters.
	 */
	public static function logical_and( bool $boolean1, bool $boolean2 ) : bool {
		return $boolean1 && $boolean2;
	}
	/**
	 * Converts a bool to a 'yes' or 'no'.
	 *
	 * @since   1.4.4
	 * @version 1.4.4
	 *
	 * @param   bool    $boolean    Bool to convert.
	 *
	 * @return  string
	 */
	public static function to_string( bool $boolean ) : string {
		return $boolean ? 'yes' : 'no';
	}
}
