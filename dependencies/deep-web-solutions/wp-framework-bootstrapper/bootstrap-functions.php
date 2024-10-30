<?php

/**
 * Defines module-specific getters and functions.
 *
 * @since   1.1.0
 * @version 1.1.3
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Bootstrapper
 *
 * @noinspection PhpMissingReturnTypeInspection
 * @noinspection PhpMissingParamTypeInspection
 */
namespace DWS_IC_Deps\DeepWebSolutions\Framework;

\defined( 'ABSPATH' ) || exit;
/**
 * Returns the whitelabel name of the framework's bootstrapper within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_bootstrapper_name() {
	 return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_NAME' );
}
/**
 * Returns the version of the framework's bootstrapper within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_bootstrapper_version() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_VERSION' );
}
/**
 * Returns the minimum PHP version required to run the Bootstrapper of the framework's bootstrapper within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_bootstrapper_min_php() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_MIN_PHP' );
}
/**
 * Returns the minimum WP version required to run the Bootstrapper of the framework's bootstrapper within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @return  string
 */
function dws_wp_framework_get_bootstrapper_min_wp() {
	return \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_MIN_WP' );
}
/**
 * Returns whether the bootstrapper has managed to initialize successfully or not in the current environment.
 *
 * @since   1.0.0
 * @version 1.1.3
 *
 * @return  bool
 */
function dws_wp_framework_get_bootstrapper_init_status() {
	return \defined( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_INIT' ) && \constant( __NAMESPACE__ . '\\DWS_WP_FRAMEWORK_BOOTSTRAPPER_INIT' );
}
/**
 * Checks if the system requirements are met.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @param   string  $min_php_version    The minimum PHP version required to run.
 * @param   string  $min_wp_version     The minimum WP version required to run.
 *
 * @return  bool
 */
function dws_wp_framework_check_php_wp_requirements_met( $min_php_version, $min_wp_version ) {
	if ( \version_compare( \PHP_VERSION, $min_php_version, '<' ) ) {
		return \false;
	} elseif ( \version_compare( $GLOBALS['wp_version'], $min_wp_version, '<' ) ) {
		return \false;
	}
	return \true;
}
/**
 * Prints an error that the system requirements weren't met.
 *
 * @since   1.0.0
 * @version 1.1.1
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 *
 * @param   string  $component_name     The name of the component that wants to record the error.
 * @param   string  $component_version  The version of the component that wants to record the error.
 * @param   string  $min_php_version    The minimum PHP version required to run.
 * @param   string  $min_wp_version     The minimum WP version required to run.
 * @param   array   $args               Associative array of other variables that should be made available in the template's context.
 */
function dws_wp_framework_output_requirements_error( $component_name, $component_version, $min_php_version, $min_wp_version, array $args = array() ) {
	if ( \did_action( 'admin_notices' ) ) {
		\_doing_it_wrong( __FUNCTION__, 'The requirements error message cannot be outputted after the admin_notices action has been already executed.', '1.0.0' );
	} else {
		\add_action(
			'admin_notices',
			function () use ( $component_name, $component_version, $min_php_version, $min_wp_version, $args ) {
				require_once __DIR__ . '/src/templates/requirements-error.php';
			}
		);
	}
}
