<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\FileSystem;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Request;
\defined( 'ABSPATH' ) || exit;
/**
 * Defines methods for retrieving instances of the WP Files API objects.
 *
 * @since   1.0.0
 * @version 1.0.2
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\FileSystem
 */
trait FilesystemAwareTrait {

	/**
	 * Returns an instance of the WP_Filesystem API. Upon failure, return null.
	 *
	 * @since   1.0.0
	 * @version 1.0.2
	 *
	 * @param   string|null     $form_url       The URL to post the form to.
	 * @param   string          $context        Full path to the directory that is tested for being writable. Default empty.
	 * @param   array|null      $extra_fields   Extra `POST` fields to be checked for inclusion in the post. Default null.
	 *
	 * @return  \WP_Filesystem_Base|null
	 */
	public function get_wp_filesystem( ?string $form_url = null, string $context = '', ?array $extra_fields = null ) : ?\WP_Filesystem_Base {
		global $wp_filesystem;
		if ( ! \function_exists( '\request_filesystem_credentials' ) ) {
			include_once ABSPATH . 'wp-admin/includes/file.php';
		}
		$form_url    = $form_url ?? \site_url();
		$credentials = \request_filesystem_credentials( $form_url, '', \false, $context, $extra_fields );
		if ( \false === $credentials ) {
			if ( \true === Request::has_debug() ) {
				\error_log( 'Failed to retrieve WP file system credentials.' );
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions
			}
			return null;
		}
		if ( \true !== \WP_Filesystem( $credentials ) ) {
			if ( \true === Request::has_debug() ) {
				\error_log( 'Failed to connect to the WP file system.' );
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions
			}
			\request_filesystem_credentials( $form_url, '', \true, $context, $extra_fields );
			return null;
		}
		return $wp_filesystem;
	}
}
