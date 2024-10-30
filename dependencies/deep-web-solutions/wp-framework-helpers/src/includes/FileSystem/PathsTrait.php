<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\FileSystem;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\ReflectionTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Defines a few short-hand methods for retrieving file system and URL paths.
 *
 * @since   1.0.0
 * @version 1.5.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\FileSystem
 */
trait PathsTrait {

	// region TRAITS
	use ReflectionTrait;
	// endregion
	// region METHODS
	/**
	 * Returns the filesystem path to the file that the using class is defined in.
	 *
	 * @since   1.5.0
	 * @version 1.5.0
	 *
	 * @param   bool    $absolute           Whether to return an absolute or a relative path.
	 * @param   bool    $keep_file_name     Whether to keep the filename or not.
	 *
	 * @return  string
	 */
	final public static function get_path( bool $absolute = \true, bool $keep_file_name = \false ) : string {
		$absolute_path = self::get_reflection_class()->getFileName();
		$path          = \wp_normalize_path( $absolute ? $absolute_path : '/' . \str_replace( ABSPATH, '', $absolute_path ) );
		return $keep_file_name ? $path : \trailingslashit( \dirname( $path ) );
	}
	/**
	 * Returns the URL to the file that the using class is defined in.
	 *
	 * @since   1.5.0
	 * @version 1.5.0
	 *
	 * @param   bool    $relative           Whether to return a relative URL or not.
	 * @param   bool    $keep_file_name     Whether to keep the filename or not.
	 *
	 * @return  string
	 */
	final public static function get_url( bool $relative = \true, bool $keep_file_name = \false ) : string {
		$relative_url = self::get_path( \false, $keep_file_name );
		return $relative ? $relative_url : \site_url( $relative_url );
	}
	// endregion
}
