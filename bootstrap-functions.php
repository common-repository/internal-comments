<?php
/**
 * Defines plugin-specific getters and functions.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Plugins\InternalComments
 *
 * @noinspection PhpMissingReturnTypeInspection
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the whitelabel name of the plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_ic_name() {
	return defined( 'DWS_IC_NAME' )
		? DWS_IC_NAME : 'Internal Comments';
}

/**
 * Returns the version of the plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string|null
 */
function dws_ic_version() {
	return defined( 'DWS_IC_VERSION' )
		? DWS_IC_VERSION : null;
}

/**
 * Returns the path to the plugin's main file.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string|null
 */
function dws_ic_path() {
	return defined( 'DWS_IC_PATH' )
		? DWS_IC_PATH : null;
}

/**
 * Returns the minimum PHP version required to run the plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string|null
 */
function dws_ic_min_php() {
	return defined( 'DWS_IC_MIN_PHP' )
		? DWS_IC_MIN_PHP : null;
}

/**
 * Returns the minimum WP version required to run the plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string|null
 */
function dws_ic_min_wp() {
	return defined( 'DWS_IC_MIN_WP' )
		? DWS_IC_MIN_WP : null;
}
