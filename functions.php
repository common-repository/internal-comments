<?php

use  DeepWebSolutions\Plugins\InternalComments\Plugin ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializationFailureException ;
use  DWS_IC_Deps\DI\Container ;
use  DWS_IC_Deps\DI\ContainerBuilder ;
defined( 'ABSPATH' ) || exit;
// region DEPENDENCY INJECTION
/**
 * Returns a container singleton that enables one to setup unit testing by passing an environment file for class mapping in PHP-DI.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @param   string  $environment    The environment rules that the container should be initialized on.
 *
 * @noinspection PhpDocMissingThrowsInspection
 *
 * @return  Container
 */
function dws_ic_di_container( string $environment = 'prod' ) : Container
{
    static  $container = null ;
    
    if ( is_null( $container ) ) {
        $container_builder = new ContainerBuilder();
        $container_builder->addDefinitions( __DIR__ . "/config_{$environment}.php" );
        /* @noinspection PhpUnhandledExceptionInspection */
        $container = $container_builder->build();
    }
    
    return $container;
}

/**
 * Returns the plugin's main class instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @noinspection PhpDocMissingThrowsInspection
 *
 * @return  Plugin
 */
function dws_ic_instance() : Plugin
{
    /* @noinspection PhpUnhandledExceptionInspection */
    return dws_ic_di_container()->get( Plugin::class );
}

/**
 * Returns a plugin component by its container ID.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @param   string  $component_id   The ID of the component as defined in the DI container.
 *
 * @return  AbstractPluginFunctionality|null
 */
function dws_ic_component( string $component_id ) : ?AbstractPluginFunctionality
{
    try {
        return dws_ic_di_container()->get( $component_id );
    } catch ( Exception $e ) {
        return null;
    }
}

// endregion
// region LIFECYCLE
/**
 * Initialization function shortcut.
 *
 * @since   1.0.0
 * @version 1.1.0
 *
 * @return  InitializationFailureException|null
 */
function dws_ic_instance_initialize() : ?InitializationFailureException
{
    $result = dws_ic_instance()->initialize();
    
    if ( is_null( $result ) ) {
        do_action( 'dws_ic_initialized' );
    } else {
        do_action( 'dws_ic_initialization_failure', $result );
    }
    
    return $result;
}

/**
 * Activate function shortcut.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
function dws_ic_plugin_activate()
{
    if ( is_null( dws_ic_instance_initialize() ) ) {
        dws_ic_instance()->activate();
    }
}

/**
 * Uninstall function shortcut.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
function dws_ic_plugin_uninstall()
{
    if ( is_null( dws_ic_instance_initialize() ) ) {
        dws_ic_instance()->uninstall();
    }
}

add_action( 'fs_after_uninstall_internal-comments', 'dws_ic_plugin_uninstall' );
// endregion
// region HOOKS
/**
 * Shorthand for generating a plugin-level hook tag.
 *
 * @since   1.1.0
 * @version 1.2.0
 *
 * @param   string              $name       The actual descriptor of the hook's purpose.
 * @param   string|string[]     $extra      Further descriptor of the hook's purpose.
 *
 * @return  string
 */
function dws_ic_get_hook_tag( string $name, $extra = array() ) : string
{
    return dws_ic_instance()->get_hook_tag( $name, $extra );
}

/**
 * Shorthand for generating a component-level hook tag.
 *
 * @since   1.1.0
 * @version 1.2.0
 *
 * @param   string              $component_id   The ID of the component as defined in the DI container.
 * @param   string              $name           The actual descriptor of the hook's purpose.
 * @param   string|string[]     $extra          Further descriptor of the hook's purpose.
 *
 * @return  string|null
 */
function dws_ic_get_component_hook_tag( string $component_id, string $name, $extra = array() ) : ?string
{
    $component = dws_ic_component( $component_id );
    if ( is_null( $component ) ) {
        return null;
    }
    if ( !did_action( 'dws_ic_initialized' ) ) {
        $component->set_plugin( dws_ic_instance() );
    }
    return $component->get_hook_tag( $name, $extra );
}

// endregion
// region OTHERS
require plugin_dir_path( __FILE__ ) . 'src/functions/comments.php';
require plugin_dir_path( __FILE__ ) . 'src/functions/output.php';
require plugin_dir_path( __FILE__ ) . 'src/functions/settings.php';
// endregion