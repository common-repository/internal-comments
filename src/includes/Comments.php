<?php

namespace DeepWebSolutions\Plugins\InternalComments;

use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\Actions\Installable\UninstallFailureException ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\Actions\UninstallableInterface ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\AssetsHelpersTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Assets ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Integers ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Actions\SetupHooksTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService ;
\defined( 'ABSPATH' ) || exit;
/**
 * Handles customization of the comment objects themselves.
 *
 * @since   1.0.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class Comments extends AbstractPluginFunctionality implements  UninstallableInterface 
{
    // region TRAITS
    use  AssetsHelpersTrait ;
    use  SetupHooksTrait ;
    // endregion
    // region INHERITED METHODS
    /**
     * {@inheritDoc}
     *
     * @since   1.1.0
     * @version 1.1.0
     */
    protected function get_di_container_children() : array
    {
        $children = array( Comments\ListTable::class, Comments\ReplyComments::class );
        return $children;
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.2.0
     */
    public function register_hooks( HooksService $hooks_service ) : void
    {
        $hooks_service->add_action( 'admin_print_styles-comment.php', $this, 'enqueue_admin_styles' );
        $hooks_service->add_filter(
            'get_comment_link',
            $this,
            'filter_comment_link',
            9999,
            2
        );
        $hooks_service->add_filter( 'admin_body_class', $this, 'filter_body_classes' );
    }
    
    // endregion
    // region HOOKS
    /**
     * Registers the admin edit-comment styles.
     *
     * @since   1.2.0
     * @version 1.2.0
     */
    public function enqueue_admin_styles()
    {
        $plugin = $this->get_plugin();
        $minified_path = Assets::maybe_get_minified_path( $plugin::get_plugin_assets_url() . 'dist/css/edit-comment.css' );
        \wp_enqueue_style(
            $this->get_asset_handle( 'edit' ),
            $minified_path,
            array(),
            Assets::maybe_get_mtime_version( $minified_path, $plugin->get_plugin_version() )
        );
    }
    
    /**
     * Replaces the posts' permalink with the posts' edit link.
     *
     * @since   1.0.0
     * @version 1.1.0
     *
     * @param   string          $link       The comment permalink with '#comment-$id' appended.
     * @param   \WP_Comment     $comment    The current comment object.
     *
     * @return  string
     */
    public function filter_comment_link( string $link, \WP_Comment $comment ) : string
    {
        
        if ( true === dws_ic_is_internal_comment( $comment ) ) {
            $link = \get_edit_post_link( $comment->comment_post_ID );
            $link .= '#comment-' . $comment->comment_ID;
        }
        
        return $link;
    }
    
    /**
     * Maybe mark the current HTML page for being related to an internal comment through a body class.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   string  $classes    List of HTML body classes.
     *
     * @return  string
     */
    public function filter_body_classes( string $classes ) : string
    {
        $comment_id = Integers::maybe_cast_input( INPUT_GET, 'c' );
        
        if ( true === dws_ic_is_internal_comment( $comment_id ) ) {
            $classes .= ' dws-internal-comment ';
            $comment = new \DWS_Internal_Comment( \get_comment( $comment_id ) );
            if ( $comment->can_trash() ) {
                $classes .= ' dws-can-trash ';
            }
        }
        
        return $classes;
    }
    
    // endregion
    // region INSTALLATION METHODS
    /**
     * Maybe remove all internal comments on uninstallation.
     *
     * @since   1.0.0
     * @version 1.1.0
     *
     * @return  UninstallFailureException|null
     */
    public function uninstall() : ?UninstallFailureException
    {
        global  $wpdb ;
        $internal_comments = $wpdb->get_results( "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_type = 'dws_internal'", ARRAY_N );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        foreach ( $internal_comments as $internal_comment ) {
            if ( !\wp_delete_comment( $internal_comment, true ) ) {
                return new UninstallFailureException( \sprintf(
                    /* translators: internal comment ID */
                    \__( 'Failed to delete internal comment %s', 'internal-comments' ),
                    $internal_comment
                ) );
            }
        }
        return null;
    }

}