<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes;

\defined( 'ABSPATH' ) || exit;
/**
 * A collection of useful callable helpers to be used throughout the projects.
 *
 * @since   1.4.0
 * @version 1.7.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\DataTypes
 */
final class Callables {

	/**
	 * Returns a given variable if it is a callable or a default value if not.
	 *
	 * @since   1.4.0
	 * @version 1.7.0
	 *
	 * @param   mixed           $callable   Variable to check.
	 * @param   callable|null   $default    The default value to return if check fails.
	 *
	 * @return  callable|null
	 */
	public static function validate( $callable, ?callable $default = null ) : ?callable {
		if ( \is_callable( $callable ) ) {
			return $callable;
		}
		if ( \false === \is_null( Strings::validate( $callable ) ) ) {
			$callable = \trim( $callable );
		} elseif ( \true === \is_callable( $callable, \true ) && \false === \is_null( Arrays::validate( $callable ) ) ) {
			$callable[1] = \trim( $callable[1] );
		}
		return \is_callable( $callable ) ? $callable : $default;
	}
	/**
	 * If given a callable, returns the return value of said callable otherwise the passed value itself.
	 *
	 * @since   1.4.0
	 * @version 1.7.0
	 *
	 * @param   callable|mixed  $maybe_callable     Variable to maybe evaluate.
	 * @param   array           $args               Arguments to pass on to the callable. No arguments by default.
	 *
	 * @return  mixed
	 */
	public static function maybe_resolve( $maybe_callable, array $args = array() ) {
		$callable = self::validate( $maybe_callable );
		return \is_null( $callable ) ? $maybe_callable : \call_user_func_array( $maybe_callable, $args );
	}
}
