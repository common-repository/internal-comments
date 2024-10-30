<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\FileSystem\Files;
\defined( 'ABSPATH' ) || exit;
/**
 * A collection of very useful WP asset handling helpers to be used throughout the projects.
 *
 * @since   1.0.0
 * @version 1.7.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers
 */
final class Assets {

	// region METHODS
	/**
	 * Returns the path to a minified version of a given asset, if it exists.
	 *
	 * @since   1.6.0
	 * @version 1.7.0
	 *
	 * @param   string                  $relative_path  Path of the asset relative to the WordPress root directory.
	 * @param   string                  $constant_name  Constant which, if set and true, the unminified path will always be returned.
	 * @param \WP_Filesystem_Base|null  $wp_filesystem  WordPress Filesystem object.
	 *
	 * @return  string
	 */
	public static function maybe_get_minified_path( string $relative_path, string $constant_name = 'SCRIPT_DEBUG', ?\WP_Filesystem_Base $wp_filesystem = null ) : string {
		$maybe_minified_path = $relative_path;
		$minified_suffix     = self::maybe_get_minified_suffix( $constant_name );
		if ( ! empty( $minified_suffix ) && \true !== Strings::contains( $relative_path, $minified_suffix ) ) {
			$wp_filesystem = $wp_filesystem ?? self::resolve_filesystem();
			if ( $wp_filesystem instanceof \WP_Filesystem_Base ) {
				$abs_path  = Files::generate_full_path( $wp_filesystem->abspath(), $relative_path );
				$extension = Strings::maybe_prefix( \pathinfo( $abs_path, \PATHINFO_EXTENSION ), '.' );
				// pathinfo returns the extension without the dot
				$minified_rel_path = Strings::maybe_suffix( Strings::maybe_unsuffix( $relative_path, $extension ), $minified_suffix . $extension );
				$minified_abs_path = Files::generate_full_path( $wp_filesystem->abspath(), $minified_rel_path );
				if ( \true === $wp_filesystem->is_file( $minified_abs_path ) ) {
					$maybe_minified_path = $minified_rel_path;
				}
			}
		}
		return $maybe_minified_path;
	}
	/**
	 * Returns a given asset's version string based on its disk modified time.
	 *
	 * @since   1.6.0
	 * @version 1.6.1
	 *
	 * @param   string  $relative_path      Path of the asset relative to the WordPress root directory.
	 * @param   string  $fallback_version   Fallback version string to return if retrieving the modified time fails.
	 * @param   \WP_Filesystem_Base|null    $wp_filesystem  WordPress Filesystem object.
	 *
	 * @return  string
	 */
	public static function maybe_get_mtime_version( string $relative_path, string $fallback_version, ?\WP_Filesystem_Base $wp_filesystem = null ) : string {
		$maybe_mtime_version = $fallback_version;
		$wp_filesystem       = $wp_filesystem ?? self::resolve_filesystem();
		if ( $wp_filesystem instanceof \WP_Filesystem_Base ) {
			$mtime               = $wp_filesystem->mtime( Files::generate_full_path( $wp_filesystem->abspath(), $relative_path ) );
			$maybe_mtime_version = ! empty( $mtime ) ? (string) $mtime : $maybe_mtime_version;
		}
		return $maybe_mtime_version;
	}
	/**
	 * Returns the assets suffix which determines whether assets should be enqueued minified or not.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @param   string  $constant_name  The name of the constant to check for truthful values in case the assets should be loaded in a minified state.
	 *
	 * @return  string
	 */
	public static function maybe_get_minified_suffix( string $constant_name = 'SCRIPT_DEBUG' ) : string {
		return Constants::is_true( $constant_name ) ? '' : '.min';
	}
	// endregion
	// region HELPERS
	/**
	 * Attempts to return a WP Filesystem object given a potentially null one.
	 *
	 * @since   1.6.1
	 * @version 1.6.1
	 *
	 * @return  \WP_Filesystem_Base|null
	 */
	private static function resolve_filesystem() : ?\WP_Filesystem_Base {
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) ) {
			\WP_Filesystem();
		}
		return $wp_filesystem instanceof \WP_Filesystem_Base ? $wp_filesystem : null;
	}
	// endregion
}
