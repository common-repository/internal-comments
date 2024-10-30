<?php

defined( 'ABSPATH' ) || exit;

/**
 * Includes a given HTML view file.
 *
 * @since   1.2.0
 * @version 1.2.0
 *
 * @param   string  $view   Relative path to the view file.
 * @param   array   $args   Variables to make available to the view.
 */
function dws_ic_include_view( string $view, array $args = array() ) {
	extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
	include dws_ic_instance()::get_plugin_custom_path( 'views', true ) . $view;
}
