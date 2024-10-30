<?php

/**
 * Defines whitelabel-ing constants and getters.
 *
 * @since   1.0.0
 * @version 1.3.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Bootstrapper
 *
 * @noinspection PhpMissingReturnTypeInspection
 */
namespace DWS_IC_Deps\DeepWebSolutions\Framework;

\defined( 'ABSPATH' ) || exit;
// region CONSTANTS
\defined( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_NAME' ) || \define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_NAME', 'Deep Web Solutions' );
\defined( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_LOGO_PATH' ) || \define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_LOGO_PATH', \wp_normalize_path( __DIR__ . '/src/assets/dws_logo.svg' ) );
\defined( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_SUPPORT_EMAIL' ) || \define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_SUPPORT_EMAIL', 'support@deep-web-solutions.com' );
\defined( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_SUPPORT_URL' ) || \define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_SUPPORT_URL', 'https://www.deep-web-solutions.com/support/' );
$dws_bootstrapper_wp_upload_dir = \wp_get_upload_dir();
\defined( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_TEMP_DIR_NAME' ) || \define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_TEMP_DIR_NAME', 'deep-web-solutions' );
\defined( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_TEMP_DIR_PATH' ) || \define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_TEMP_DIR_PATH', \wp_normalize_path( $dws_bootstrapper_wp_upload_dir['basedir'] . '/' . dws_wp_framework_get_temp_dir_name() . '/' ) );
\defined( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_TEMP_DIR_URL' ) || \define( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_TEMP_DIR_URL', $dws_bootstrapper_wp_upload_dir['baseurl'] . '/' . dws_wp_framework_get_temp_dir_name() . '/' );
// endregion
// region GETTERS
/**
 * Returns the whitelabel name of the framework within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_whitelabel_name() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_NAME' );
}
/**
 * Returns the path to the whitelabel logo of the framework within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_whitelabel_logo_path() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_LOGO_PATH' );
}
/**
 * Returns the whitelabel support email of the framework within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_whitelabel_support_email() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_SUPPORT_EMAIL' );
}
/**
 * Returns the whitelabel support URL of the framework within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_whitelabel_support_url() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_WHITELABEL_SUPPORT_URL' );
}
/**
 * Returns the name of the temp directory within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_temp_dir_name() {
	 return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_TEMP_DIR_NAME' );
}
/**
 * Returns the path to the temp directory within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_temp_dir_path() {
	 return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_TEMP_DIR_PATH' );
}
/**
 * Returns the URL of the temp directory within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_temp_dir_url() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_TEMP_DIR_URL' );
}
// endregion
