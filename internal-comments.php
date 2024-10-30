<?php
/**
 * The Internal Comments bootstrap file.
 *
 * @since               1.0.0
 * @version             1.2.0
 * @package             DeepWebSolutions\WP-Plugins\InternalComments
 * @author              Deep Web Solutions
 * @copyright           2021 Deep Web Solutions
 * @license             GPL-3.0-or-later
 *
 * @noinspection        ALL
   *
 * @wordpress-plugin
 * Plugin Name:             Internal Comments
 * Plugin URI:              https://www.deep-web-solutions.com/plugins/internal-comments/
 * Description:             A WordPress plugin for administrators and other users with admin area access to post private admin-side comments to any registered post type.
 * Version:                 1.2.4
 * Requires at least:       5.5
 * Requires PHP:            7.4
 * Author:                  Deep Web Solutions
 * Author URI:              https://www.deep-web-solutions.com
 * License:                 GPL-3.0+
 * License URI:             http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:             internal-comments
 * Domain Path:             /src/languages
 */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'dws_ic_fs' ) ) {
	dws_ic_fs()->set_basename( false, __FILE__ );
	return;
}

// Start by autoloading dependencies and defining a few functions for running the bootstrapper.
is_file( __DIR__ . '/vendor/autoload.php' ) && require_once __DIR__ . '/vendor/autoload.php';

// Load plugin-specific bootstrapping functions.
require_once __DIR__ . '/bootstrap-functions.php';

// Check that the DWS WP Framework is loaded.
if ( ! function_exists( '\DWS_IC_Deps\DeepWebSolutions\Framework\dws_wp_framework_get_bootstrapper_init_status' ) ) {
	add_action(
		'admin_notices',
		function() {
			$message      = wp_sprintf( /* translators: %s: Plugin name. */ __( 'It seems like <strong>%s</strong> is corrupted. Please reinstall!', 'internal-comments' ), dws_ic_name() );
			$html_message = wp_sprintf( '<div class="error notice dws-plugin-corrupted-error">%s</div>', wpautop( $message ) );
			echo wp_kses_post( $html_message );
		}
	);
	return;
}

// Define plugin constants.
define( 'DWS_IC_NAME', DWS_IC_Deps\DeepWebSolutions\Framework\dws_wp_framework_get_whitelabel_name() . ': Internal Comments' );
define( 'DWS_IC_VERSION', '1.2.4' );
define( 'DWS_IC_PATH', __FILE__ );

// Define minimum environment requirements.
define( 'DWS_IC_MIN_PHP', '7.4' );
define( 'DWS_IC_MIN_WP', '5.5' );

// Start plugin initialization if system requirements check out.
if ( DWS_IC_Deps\DeepWebSolutions\Framework\dws_wp_framework_check_php_wp_requirements_met( dws_ic_min_php(), dws_ic_min_wp() ) ) {
	if ( ! \function_exists( 'dws_ic_fs' ) ) {
		include __DIR__ . '/freemius.php';
		dws_ic_fs_init();
	}

	include __DIR__ . '/functions.php';

	add_action( 'plugins_loaded', 'dws_ic_instance_initialize' );
	register_activation_hook( __FILE__, 'dws_ic_plugin_activate' );
} else {
	DWS_IC_Deps\DeepWebSolutions\Framework\dws_wp_framework_output_requirements_error( dws_ic_name(), dws_ic_version(), dws_ic_min_php(), dws_ic_min_wp() );
}
