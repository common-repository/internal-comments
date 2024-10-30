<?php

/**
 * Defines module-specific getters and functions.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 *
 * @noinspection PhpMissingReturnTypeInspection
 */
namespace DWS_IC_Deps\DeepWebSolutions\Framework;

\defined( 'ABSPATH' ) || exit;
/**
 * Returns the whitelabel name of the framework's settings within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_settings_name() {
	 return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_SETTINGS_NAME' );
}
/**
 * Returns the version of the framework's settings within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_settings_version() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_SETTINGS_VERSION' );
}
/**
 * Returns the minimum PHP version required to run the Bootstrapper of the framework's settings within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_settings_min_php() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_SETTINGS_MIN_PHP' );
}
/**
 * Returns the minimum WP version required to run the Bootstrapper of the framework's settings within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_settings_min_wp() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_SETTINGS_MIN_WP' );
}
/**
 * Returns whether the settings package has managed to initialize successfully or not in the current environment.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  bool
 */
function dws_wp_framework_get_settings_init_status() {
	return \defined( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_SETTINGS_INIT' ) && \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_SETTINGS_INIT' );
}
