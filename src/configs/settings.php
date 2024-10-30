<?php

use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Booleans ;
use function  DWS_IC_Deps\DI\factory ;
defined( 'ABSPATH' ) || exit;
$settings = array(
    'defaults' => array(
    'general' => array(
    'post-types' => array(),
),
    'plugin'  => array(
    'remove-data-uninstall' => Booleans::to_string( false ),
),
),
    'options'  => array(
    'general' => array(
    'post-types' => factory( function () {
    $ui_post_types = get_post_types( array(
        'show_ui' => true,
    ), 'objects' );
    return array_combine( wp_list_pluck( $ui_post_types, 'name' ), wp_list_pluck( $ui_post_types, 'label' ) );
} ),
),
),
);
return $settings;