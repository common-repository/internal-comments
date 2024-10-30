<?php

defined( 'ABSPATH' ) || exit;
/**
 * Returns the Freemius instance of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @noinspection PhpDocMissingThrowsInspection
 *
 * @return  Freemius
 */
function dws_ic_fs() : Freemius
{
    global  $dws_ic_fs ;
    
    if ( !isset( $dws_ic_fs ) ) {
        // Activate multisite network integration.
        if ( !defined( 'WP_FS__PRODUCT_8406_MULTISITE' ) ) {
            define( 'WP_FS__PRODUCT_8406_MULTISITE', true );
        }
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/vendor/freemius/wordpress-sdk/start.php';
        /* @noinspection PhpUnhandledExceptionInspection */
        $dws_ic_fs = fs_dynamic_init( array(
            'id'             => '8406',
            'slug'           => 'internal-comments',
            'type'           => 'plugin',
            'public_key'     => 'pk_8a73770d0590e3f8a7fe982ae2c1e',
            'is_premium'     => false,
            'premium_suffix' => 'Premium',
            'has_addons'     => false,
            'has_paid_plans' => true,
            'menu'           => array(
            'first-path' => 'plugins.php',
        ),
            'is_live'        => true,
        ) );
    }
    
    return $dws_ic_fs;
}

/**
 * Initializes the Freemius global instance and sets a few defaults.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  Freemius
 */
function dws_ic_fs_init() : Freemius
{
    $freemius = dws_ic_fs();
    do_action( 'dws_ic_fs_loaded' );
    $freemius->add_filter( 'after_skip_url', 'dws_ic_fs_settings_url' );
    $freemius->add_filter( 'after_connect_url', 'dws_ic_fs_settings_url' );
    $freemius->add_filter( 'after_pending_connect_url', 'dws_ic_fs_settings_url' );
    return $freemius;
}

/**
 * Returns the URL to the settings page.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_ic_fs_settings_url() : string
{
    return admin_url( 'options-general.php?page=dws-internal-comments' );
}
