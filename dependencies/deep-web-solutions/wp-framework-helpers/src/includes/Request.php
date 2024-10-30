<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
\defined( 'ABSPATH' ) || exit;
/**
 * A collection of very useful WP request helpers to be used throughout the projects.
 *
 * @since   1.0.0
 * @version 1.7.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers
 */
final class Request {

	// region METHODS
	/**
	 * An improved copy of WooCommerce's private built-in function for determining what type of request we're dealing with.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @see     WooCommerce::is_request()
	 *
	 * @param   string  $type   The type of request to check against. Supported values: admin, ajax, cron, frontend, rest.
	 *
	 * @return  bool    True if the current request is of the type passed on, false otherwise.
	 */
	public static function is_type( string $type ) : bool {
		switch ( $type ) {
			case 'admin':
				$result = \is_admin();
				break;
			case 'ajax':
				$result = \wp_doing_ajax();
				break;
			case 'cron':
				$result = \wp_doing_cron();
				break;
			case 'rest':
				$result = self::is_rest_api_request();
				break;
			case 'front':
				$result = ( ! \is_admin() || \wp_doing_ajax() ) && ! \wp_doing_cron() && ! self::is_rest_api_request();
				break;
			default:
				$result = \false;
		}
		return $result;
	}
	/**
	 * Checks whether a debugging constant is set and true-ish.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @param   string  $constant   The constant to check for. Default is WP_DEBUG.
	 *
	 * @return  bool    True if the constant is defined and true.
	 */
	public static function has_debug( string $constant = 'WP_DEBUG' ) : bool {
		return Constants::is_true( $constant );
	}
	// endregion
	// region HELPERS
	/**
	 * Returns true if the current request is a REST API request.
	 *
	 * @todo: replace this function once core WP function is available: https://core.trac.wordpress.org/ticket/42061.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 *
	 * @return  bool
	 */
	protected static function is_rest_api_request() : bool {
		if ( \function_exists( 'DWS_IC_Deps\\wp_doing_rest' ) ) {
			return \DWS_IC_Deps\wp_doing_rest();
		}
		if ( \defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return \true;
		}
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return ( \wp_is_jsonp_request() || \wp_is_json_request() ) && ! \wp_doing_ajax();
		}
		$rest_prefix         = \trailingslashit( \rest_get_url_prefix() );
		$is_rest_api_request = Strings::contains( $_SERVER['REQUEST_URI'], $rest_prefix );
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		return $is_rest_api_request || ( \wp_is_jsonp_request() || \wp_is_json_request() ) && ! \wp_doing_ajax();
	}
	// endregion
}
