<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Booleans;
\defined( 'ABSPATH' ) || exit;
/**
 * A collection of very useful WP constants helpers to be used throughout the projects.
 *
 * @see     https://github.com/Automattic/jetpack-constants
 *
 * @since   1.7.0
 * @version 1.7.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers
 */
final class Constants {

	// region FIELDS AND CONSTANTS
	/**
	 * A container for all defined constants.
	 *
	 * @since   1.7.0
	 * @version 1.7.0
	 *
	 * @var     array
	 */
	protected static array $set_constants = array();
	// endregion
	// region METHODS
	/**
	 * Sets the value of the "constant" within constants helper.
	 *
	 * @since   1.7.0
	 * @version 1.7.0
	 *
	 * @param   string                              $name   The name of the constant.
	 * @param   null|array|bool|int|float|string    $value  The value of the constant.
	 */
	public static function set( string $name, $value ) : void {
		self::$set_constants[ $name ] = $value;
	}
	/**
	 * Attempts to retrieve the "constant" from constants helper, and if it hasn't been set, then attempts to get the constant with the constant() function.
	 * If that also hasn't been set, attempts to get a value from filters.
	 *
	 * @since   1.7.0
	 * @version 1.7.0
	 *
	 * @param   string  $name   The name of the constant.
	 *
	 * @return  null|array|bool|int|float|string    Null if the constant does not exist or the value of the constant.
	 */
	public static function get( string $name ) {
		if ( \array_key_exists( $name, self::$set_constants ) ) {
			return self::$set_constants[ $name ];
		}
		if ( \defined( $name ) ) {
			return \constant( $name );
		}
		return null;
	}
	/**
	 * Unsets a constant from the helper by its given name if defined "inside" of it.
	 *
	 * @since   1.7.0
	 * @version 1.7.0
	 *
	 * @param   string  $name   The name of the constant.
	 *
	 * @return  bool
	 */
	public static function clear_single( string $name ) : bool {
		if ( ! \array_key_exists( $name, self::$set_constants ) ) {
			return \false;
		}
		unset( self::$set_constants[ $name ] );
		return \true;
	}
	/**
	 * Unsets all the constants defined "inside" the helper.
	 *
	 * @since   1.7.0
	 * @version 1.7.0
	 *
	 * @return  void
	 */
	public static function clear_all() : void {
		self::$set_constants = array();
	}
	/**
	 * Checks if a "constant" has been set in constants Manager, and if not, checks if the constant was defined with define( 'name', 'value ).
	 *
	 * @since   1.7.0
	 * @version 1.7.0
	 *
	 * @param   string  $name   The name of the constant.
	 *
	 * @return  bool
	 */
	public static function is_defined( string $name ) : bool {
		return \array_key_exists( $name, self::$set_constants ) || \defined( $name );
	}
	/**
	 * Checks whether a given constant is defined and contains something that can be evaluated as a boolean 'true'.
	 *
	 * @since   1.7.0
	 * @version 1.7.0
	 *
	 * @param   string  $name   The name of the constant.
	 *
	 * @return  bool
	 */
	public static function is_true( string $name ) : bool {
		return self::is_defined( $name ) && Booleans::maybe_cast( self::get( $name ), \false );
	}
	// endregion
}
