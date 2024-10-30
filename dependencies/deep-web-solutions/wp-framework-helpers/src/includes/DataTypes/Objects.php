<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes;

\defined( 'ABSPATH' ) || exit;
/**
 * A collection of very useful misc helpers to be used throughout the projects.
 *
 * @since   1.0.0
 * @version 1.7.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\DataTypes
 */
final class Objects {

	/**
	 * Returns a given variable if it is an object or a default value if not.
	 *
	 * @since   1.4.0
	 * @version 1.4.0
	 *
	 * @param   mixed           $object     Variable to check.
	 * @param   object|null     $default    The default value to return if check fails. By default null.
	 *
	 * @return  object|null
	 */
	public static function validate( $object, ?object $default = null ) : ?object {
		return \is_object( $object ) ? $object : $default;
	}
	/**
	 * Returns all the traits used by a trait and its traits as a hierarchical array.
	 *
	 * @since   1.2.0
	 * @version 1.7.0
	 *
	 * @SuppressWarnings(PHPMD.UndefinedVariable)
	 *
	 * @param   string  $trait          The trait to act upon.
	 * @param   bool    $autoload       Whether to allow this function to load the class automatically through the __autoload() magic method.
	 *
	 * @return  array[]|null
	 */
	public static function trait_uses_deep( string $trait, bool $autoload = \true ) : ?array {
		static $results = array();
		if ( isset( $results[ $trait ] ) ) {
			$traits = $results[ $trait ];
		} else {
			$traits      = null;
			$used_traits = Arrays::validate( \class_uses( $trait, $autoload ), null );
			if ( \false === \is_null( $used_traits ) ) {
				$traits = array();
				foreach ( $used_traits as $used_trait ) {
					$traits[ $used_trait ] = self::trait_uses_deep( $used_trait, $autoload );
				}
				if ( \true === $autoload ) {
					$results[ $trait ] = $traits;
				}
			}
		}
		return $traits;
	}
	/**
	 * Returns all the traits used by a trait and its traits as a list array.
	 *
	 * @since   1.2.0
	 * @version 1.7.0
	 *
	 * @SuppressWarnings(PHPMD.UndefinedVariable)
	 *
	 * @param   string  $trait          The trait to act upon.
	 * @param   bool    $autoload       Whether to allow this function to load the class automatically through the __autoload() magic method.
	 *
	 * @return  string[]|null
	 */
	public static function trait_uses_deep_list( string $trait, bool $autoload = \true ) : ?array {
		static $results = array();
		if ( isset( $results[ $trait ] ) ) {
			$traits = $results[ $trait ];
		} else {
			$traits      = null;
			$used_traits = self::trait_uses_deep( $trait, $autoload );
			if ( \false === \is_null( $used_traits ) ) {
				$traits = array();
				foreach ( $used_traits as $used_trait => $children_traits ) {
					$traits[ $used_trait ] = $used_trait;
					foreach ( \array_keys( $children_traits ) as $child_trait ) {
						$traits[ $child_trait ] = $child_trait;
						$traits                += self::trait_uses_deep_list( $child_trait, $autoload );
					}
				}
				if ( \true === $autoload ) {
					$results[ $trait ] = $traits;
				}
			}
		}
		return $traits;
	}
	/**
	 * Returns all traits used by a class, its parent classes and trait of their traits as a hierarchical array.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @SuppressWarnings(PHPMD.UndefinedVariable)
	 *
	 * @see     https://www.php.net/manual/en/function.class-uses.php#122427
	 *
	 * @param   object|string   $class          An object (class instance) or a string (class name).
	 * @param   bool            $autoload       Whether to allow this function to load the class automatically through the __autoload() magic method.
	 *
	 * @return  array[]|null
	 */
	public static function class_uses_deep( $class, bool $autoload = \true ) : ?array {
		static $results = array();
		$class          = \is_object( $class ) ? \get_class( $class ) : (string) $class;
		if ( isset( $results[ $class ] ) ) {
			$traits = $results[ $class ];
		} else {
			$traits        = null;
			$class_parents = Arrays::validate( \class_parents( $class ), null );
			if ( \false === \is_null( $class_parents ) ) {
				$traits = array();
				foreach ( \array_reverse( $class_parents ) + array( $class => $class ) as $current_class ) {
					$traits[ $current_class ] = self::trait_uses_deep( $current_class, $autoload );
				}
				if ( \true === $autoload ) {
					$results[ $class ] = $traits;
				}
			}
		}
		return $traits;
	}
	/**
	 * Returns all traits used by a class, its parent classes and trait of their traits as a list array.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @SuppressWarnings(PHPMD.UndefinedVariable)
	 *
	 * @param   object|string   $class          An object (class instance) or a string (class name).
	 * @param   bool            $autoload       Whether to allow this function to load the class automatically through the __autoload() magic method.
	 *
	 * @return  string[]|null
	 */
	public static function class_uses_deep_list( $class, bool $autoload = \true ) : ?array {
		static $results = array();
		$class          = \is_object( $class ) ? \get_class( $class ) : (string) $class;
		if ( isset( $results[ $class ] ) ) {
			$traits = $results[ $class ];
		} else {
			$traits      = null;
			$used_traits = self::class_uses_deep( $class, $autoload );
			if ( \false === \is_null( $used_traits ) ) {
				$traits = array();
				foreach ( $used_traits as $traits_list ) {
					foreach ( \array_keys( $traits_list ) as $used_trait ) {
						$traits[ $used_trait ] = $used_trait;
						$traits               += self::trait_uses_deep_list( $used_trait, $autoload );
					}
				}
				if ( \true === $autoload ) {
					$results[ $class ] = $traits;
				}
			}
		}
		return $traits;
	}
	/**
	 * Returns whether a given trait is used in a given class/trait.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @param   string          $trait              Trait to search for.
	 * @param   object|string   $class_or_trait     An object (class instance) or a string (class or trait name) to investigate.
	 * @param   bool            $autoload           Whether to allow this function to load the class automatically through the __autoload() magic method.
	 *
	 * @return  bool|null
	 */
	public static function has_trait( string $trait, $class_or_trait, bool $autoload = \true ) : ?bool {
		$traits = Arrays::validate( \class_uses( $class_or_trait, $autoload ), null );
		return \is_null( $traits ) ? null : isset( $traits[ $trait ] );
	}
	/**
	 * Returns whether a given trait is used in the inheritance tree of a given class/trait.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @param   string          $trait          Trait to search for.
	 * @param   object|string   $class_or_trait An object (class instance) or a string (class or trait name) to investigate.
	 * @param   bool            $autoload       Whether to allow this function to load the class automatically through the __autoload() magic method.
	 *
	 * @return  bool|null
	 */
	public static function has_trait_deep( string $trait, $class_or_trait, bool $autoload = \true ) : ?bool {
		if ( \true === \is_object( $class_or_trait ) || \true === \class_exists( $class_or_trait, $autoload ) ) {
			return isset( self::class_uses_deep_list( $class_or_trait, $autoload )[ $trait ] );
		}
		if ( \true === \trait_exists( $class_or_trait, $autoload ) ) {
			return isset( self::trait_uses_deep_list( $class_or_trait, $autoload )[ $trait ] );
		}
		return null;
	}
}
