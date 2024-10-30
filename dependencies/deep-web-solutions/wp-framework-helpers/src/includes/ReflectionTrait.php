<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers;

\defined( 'ABSPATH' ) || exit;
/**
 * Defines a few shorthand methods for retrieving reflection information about the using class.
 *
 * @since   1.0.0
 * @version 1.5.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers
 */
trait ReflectionTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Collection of reflection class instances for static compatibility with class inheritance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  private
	 * @var     \ReflectionClass[]
	 */
	protected static array $reflection_class = array();
	// endregion
	// region GETTERS
	/**
	 * Gets the reflection class instance of the using class.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  \ReflectionClass
	 */
	final public static function get_reflection_class() : \ReflectionClass {
		if ( ! isset( self::$reflection_class[ static::class ] ) ) {
			self::$reflection_class[ static::class ] = new \ReflectionClass( static::class );
		}
		return self::$reflection_class[ static::class ];
	}
	// endregion
	// region METHODS
	/**
	 * Gets the full name of the using class.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 * @link    https://www.php.net/manual/en/language.namespaces.rules.php
	 *
	 * @param   bool    $fully      Whether to return a fully-qualified name or just a qualified one.
	 *
	 * @return  string
	 */
	final public static function get_qualified_class_name( bool $fully = \true ) : string {
		$qualified_class_name = self::get_reflection_class()->getName();
		return $fully ? '\\' . \ltrim( $qualified_class_name, '\\' ) : $qualified_class_name;
	}
	/**
	 * Gets the namespace name of the using class.
	 *
	 * @since   1.5.0
	 * @version 1.5.0
	 *
	 * @return  string
	 */
	final public static function get_class_namespace() : string {
		return self::get_reflection_class()->getNamespaceName();
	}
	/**
	 * Gets the short name of the using class.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	final public static function get_class_name() : string {
		return self::get_reflection_class()->getShortName();
	}
	// endregion
}
