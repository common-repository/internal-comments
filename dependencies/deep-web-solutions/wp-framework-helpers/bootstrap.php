<?php

/**
 * The DWS WordPress Framework Helpers bootstrap file.
 *
 * @since               1.0.0
 * @version             1.7.2
 * @package             DeepWebSolutions\WP-Framework\Helpers
 * @author              Deep Web Solutions GmbH
 * @copyright           2021 Deep Web Solutions GmbH
 * @license             GPL-3.0-or-later
 *
 * @noinspection PhpMissingReturnTypeInspection
 * @noinspection StaticClosureCanBeUsedInspection
 *
 * @wordpress-plugin
 * Plugin Name:         DWS WordPress Framework Helpers
 * Description:         A set of related helpers to kick-start WordPress development.
 * Version:             1.7.2
 * Requires at least:   5.5
 * Requires PHP:        7.4
 * Author:              Deep Web Solutions GmbH
 * Author URI:          https://www.deep-web-solutions.com
 * License:             GPL-3.0+
 * License URI:         http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:         internal-comments
 * Domain Path:         /languages
 */
namespace DWS_IC_Deps\DeepWebSolutions\Framework;

if ( ! \defined( 'ABSPATH' ) ) {
	return;
	// Since this file is autoloaded by Composer, 'exit' breaks all external dev tools.
}
// Start by autoloading dependencies and defining a few functions for running the bootstrapper.
// The conditional check makes the whole thing compatible with Composer-based WP management.
\is_file( dirname( __FILE__ ) . '/vendor/autoload.php' ) && ( require_once dirname( __FILE__ ) . '/vendor/autoload.php' );
// Load module-specific bootstrapping functions.
require_once dirname( __FILE__ ) . '/bootstrap-functions.php';
// Define helpers constants.
\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_HELPERS_NAME', dws_wp_framework_get_whitelabel_name() . ': Framework Helpers' );
\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_HELPERS_VERSION', '1.7.2' );
// Define minimum environment requirements.
\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_HELPERS_MIN_PHP', '7.4' );
\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_HELPERS_MIN_WP', '5.5' );
// Bootstrap the helpers (maybe)!
if ( dws_wp_framework_check_php_wp_requirements_met( dws_wp_framework_get_helpers_min_php(), dws_wp_framework_get_helpers_min_wp() ) ) {
	$dws_helpers_init_function = function () {
		\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_HELPERS_INIT', \apply_filters( 'dws_wp_framework/helpers/init_status', dws_wp_framework_get_bootstrapper_init_status(), __NAMESPACE__ ) );
	};
	if ( \did_action( 'plugins_loaded' ) ) {
		$dws_helpers_init_function();
	} else {
		\add_action( 'plugins_loaded', $dws_helpers_init_function, \PHP_INT_MIN + 100 );
	}
} else {
	\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_HELPERS_INIT', \false );
	dws_wp_framework_output_requirements_error( dws_wp_framework_get_helpers_name(), dws_wp_framework_get_helpers_version(), dws_wp_framework_get_helpers_min_php(), dws_wp_framework_get_helpers_min_wp() );
}
