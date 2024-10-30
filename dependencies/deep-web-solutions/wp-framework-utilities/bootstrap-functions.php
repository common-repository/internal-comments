<?php

/**
 * Defines module-specific getters and functions.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities
 *
 * @noinspection PhpMissingReturnTypeInspection
 */
namespace DWS_IC_Deps\DeepWebSolutions\Framework;

\defined( 'ABSPATH' ) || exit;
/**
 * Returns the whitelabel name of the framework's utilities within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_utilities_name() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_UTILITIES_NAME' );
}
/**
 * Returns the version of the framework's utilities within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_utilities_version() {
	 return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_UTILITIES_VERSION' );
}
/**
 * Returns the minimum PHP version required to run the Bootstrapper of the framework's utilities within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_utilities_min_php() {
	 return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_UTILITIES_MIN_PHP' );
}
/**
 * Returns the minimum WP version required to run the Bootstrapper of the framework's utilities within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_utilities_min_wp() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_UTILITIES_MIN_WP' );
}
/**
 * Returns whether the utilities package has managed to initialize successfully or not in the current environment.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  bool
 */
function dws_wp_framework_get_utilities_init_status() {
	 return \defined( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_UTILITIES_INIT' ) && \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_UTILITIES_INIT' );
}
