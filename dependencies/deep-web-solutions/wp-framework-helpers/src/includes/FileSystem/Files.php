<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\FileSystem;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
\defined( 'ABSPATH' ) || exit;
/**
 * A collection of very useful file system helpers to be used throughout the projects.
 *
 * @since   1.0.0
 * @version 1.7.2
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\FileSystem
 */
final class Files {

	/**
	 * Append two halves of a full-path in a way that doesn't cause double slashes.
	 *
	 * @since   1.0.0
	 * @version 1.5.0
	 *
	 * @param   string  $absolute   The absolute path to prepend.
	 * @param   string  $relative   The relative path to append to the absolute one.
	 *
	 * @return  string  Full disk path.
	 */
	public static function generate_full_path( string $absolute, string $relative ) : string {
		$absolute = \trailingslashit( \trim( $absolute ) );
		$relative = \trim( $relative, " \t\n\r\x00\v/\\" );
		// forward and backward slashes added
		return \wp_normalize_path( $absolute . $relative );
	}
	/**
	 * Checks if a filename has a certain extension. Does NOT actually verify whether the file exists or is actually
	 * truthful about its nature.
	 *
	 * @version 1.0.0
	 * @since   1.4.6
	 *
	 * @param   string  $filename   The path to the file, or just the filename.
	 * @param   string  $extension  The extension to check against.
	 *
	 * @return  bool    Whether the file has the expected extension or not.
	 */
	public static function has_extension( string $filename, string $extension ) : bool {
		return Strings::ends_with( $filename, Strings::maybe_prefix( $extension, '.' ) );
	}
	/**
	 * Converts a given path into a URL.
	 *
	 * @since   1.7.2
	 * @version 1.7.2
	 * @see    https://github.com/wpmetabox/meta-box/blob/023e3ccc08aced428c34b542169b9ac36d3893bd/inc/fields/file.php#L523
	 *
	 * @param   string  $absolute_or_relative   Absolute or relative filesystem path within the WordPress directory.
	 *
	 * @return  string
	 */
	public static function convert_path_to_url( string $absolute_or_relative ) : string {
		$absolute_or_relative = \wp_normalize_path( \untrailingslashit( $absolute_or_relative ) );
		$wp_root              = \wp_normalize_path( \untrailingslashit( ABSPATH ) );
		$relative             = \str_replace( $wp_root, '', $absolute_or_relative );
		return \home_url( $relative );
	}
}
