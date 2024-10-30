<?php

/**
 * The DWS WordPress Framework Bootstrapper bootstrap file.
 *
 * @since               1.0.0
 * @version             1.1.1
 * @package             DeepWebSolutions\WP-Framework\Bootstrapper
 * @author              Deep Web Solutions GmbH
 * @copyright           2021 Deep Web Solutions GmbH
 * @license             GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:         DWS WordPress Framework Bootstrapper
 * Description:         A set of related functions that help bootstrap and version the other DWS WordPress Framework components.
 * Version:             1.3.0
 * Requires at least:   5.5
 * Requires PHP:        5.3
 * Author:              Deep Web Solutions GmbH
 * Author URI:          https://www.deep-web-solutions.com
 * License:             GPL-3.0+
 * License URI:         http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:         internal-comments
 * Domain Path:         /src/languages
 */
namespace DWS_IC_Deps\DeepWebSolutions\Framework;

if ( ! \defined( 'ABSPATH' ) ) {
	return;
	// Since this file is autoloaded by Composer, 'exit' breaks all external dev tools.
}
// Start by autoloading dependencies and defining a few functions for running the bootstrapper.
// The conditional check makes the whole thing compatible with Composer-based WP management.
\is_file( dirname( __FILE__ ) . '/vendor/autoload.php' ) && ( require_once dirname( __FILE__ ) . '/vendor/autoload.php' );
// Load whitelabel settings and module-specific bootstrapping functions.
require_once dirname( __FILE__ ) . '/bootstrap-whitelabel.php';
require_once dirname( __FILE__ ) . '/bootstrap-functions.php';
// Define bootstrapper constants.
\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_NAME', dws_wp_framework_get_whitelabel_name() . ': Framework Bootstrapper' );
\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_VERSION', '1.3.0' );
// Define minimum environment requirements.
\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_MIN_PHP', '7.4' );
\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_MIN_WP', '5.5' );
/**
 * Register the language files for the bootstrapper's text domain.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
\add_action(
	'init',
	function () {
		// Line removed during PHP-scoping.
	}
);
// Bootstrap the bootstrapper (maybe)!
if ( dws_wp_framework_check_php_wp_requirements_met( dws_wp_framework_get_bootstrapper_min_php(), dws_wp_framework_get_bootstrapper_min_wp() ) ) {
	\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_INIT', \true );
} else {
	\define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_INIT', \false );
	dws_wp_framework_output_requirements_error( dws_wp_framework_get_bootstrapper_name(), dws_wp_framework_get_bootstrapper_version(), dws_wp_framework_get_bootstrapper_min_php(), dws_wp_framework_get_bootstrapper_min_wp() );
}
