<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes;

\defined( 'ABSPATH' ) || exit;
/**
 * A collection of useful float helpers to be used throughout the projects.
 *
 * @since   1.4.0
 * @version 1.4.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\DataTypes
 */
final class Floats {

	/**
	 * Returns a given variable if it is a float or a default value if not.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   mixed       $float      Variable to check.
	 * @param   float|null  $default    The default value to return if check fails. By default null.
	 *
	 * @return  float|null
	 */
	public static function validate( $float, ?float $default = null ) : ?float {
		return \is_float( $float ) ? $float : $default;
	}
	/**
	 * Attempts to cast a variable of unknown type into a float.
	 *
	 * @since   1.0.0
	 * @since   1.4.0   Moved to the Floats class.
	 * @version 1.4.0
	 *
	 * @param   mixed       $float      Variable to cast.
	 * @param   float|null  $default    The default value to return if all fails. By default null.
	 *
	 * @return  float|null
	 */
	public static function maybe_cast( $float, ?float $default = null ) : ?float {
		$result = \filter_var( $float, \FILTER_VALIDATE_FLOAT, \FILTER_REQUIRE_SCALAR | \FILTER_FLAG_ALLOW_THOUSAND );
		if ( \false === $result ) {
			$result = \filter_var(
				$float,
				\FILTER_VALIDATE_FLOAT,
				array(
					'options' => array( 'decimal' => ',' ),
					'flags'   => \FILTER_REQUIRE_SCALAR | \FILTER_FLAG_ALLOW_THOUSAND,
				)
			);
		}
		return self::validate( $result, $default );
	}
	/**
	 * Attempts to cast a variable from an input stream into a float.
	 *
	 * @since   1.0.0
	 * @since   1.4.0   Moved to the Floats class.
	 * @version 1.4.0
	 *
	 * @param   int         $input_type     One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
	 * @param   string      $variable_name  Name of a variable to get from the input stream.
	 * @param   float|null  $default        The default value to return if all fails. By default null.
	 *
	 * @return  float|null
	 */
	public static function maybe_cast_input( int $input_type, string $variable_name, ?float $default = null ) : ?float {
		if ( \filter_has_var( $input_type, $variable_name ) ) {
			$float = \filter_input( $input_type, $variable_name, \FILTER_UNSAFE_RAW, \FILTER_REQUIRE_SCALAR );
			return self::maybe_cast( $float, $default );
		}
		return $default;
	}
}
