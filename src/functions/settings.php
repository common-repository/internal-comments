<?php

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;

defined( 'ABSPATH' ) || exit;

/**
 * Returns the raw database value of a plugin's option.
 *
 * @since   1.0.0
 * @version 1.1.0
 *
 * @param   string          $field_id   The ID of the settings field to retrieve.
 * @param   string|null     $group      The group to retrieve the setting from.
 *
 * @return  mixed
 */
function dws_ic_get_raw_setting( string $field_id, ?string $group = null ) {
	$group = is_null( $group ) ? 'settings' : Strings::maybe_suffix( $group, '-settings' );
	return dws_ic_component( $group )->get_option_value( $field_id );
}

/**
 * Returns the validated database value of a plugin's option.
 *
 * @since   1.1.0
 * @version 1.1.0
 *
 * @param   string          $field_id   The ID of the settings field to retrieve.
 * @param   string|null     $group      The group to retrieve the setting from.
 *
 * @return  mixed
 */
function dws_ic_get_validated_setting( string $field_id, ?string $group = null ) {
	$group = is_null( $group ) ? 'settings' : Strings::maybe_suffix( $group, '-settings' );
	return dws_ic_component( $group )->get_validated_option_value( $field_id );
}

/**
 * Helper for retrieving the list of post types that were selected for internal comments. This helper does NOT retrieve
 * the validated value because that initializes the supported post types too soon on 'plugins_loaded' inside configs/settings.php,
 * which in turn causes all available CPTs to be removed.
 *
 * @since   1.0.0
 * @version 1.1.0
 *
 * @return  array
 */
function dws_ic_get_selected_post_types(): array {
	return Arrays::validate( dws_ic_get_raw_setting( 'post-types', 'general' ), array() );
}
