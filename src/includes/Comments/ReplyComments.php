<?php

namespace DeepWebSolutions\Plugins\InternalComments\Comments;

use  DeepWebSolutions\Plugins\InternalComments\Permissions\CommentPermissions ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\AssetsHelpersTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Assets ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Booleans ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Users ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Actions\SetupHooksTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService ;
\defined( 'ABSPATH' ) || exit;
/**
 * Handles customization of the comment replies.
 *
 * @since   1.1.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class ReplyComments extends AbstractPluginFunctionality
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
     * @version 1.2.0
     */
    public function register_hooks( HooksService $hooks_service ) : void
    {
        $hooks_service->add_filter(
            dws_ic_get_hook_tag( 'create_comment_data' ),
            $this,
            'filter_reply_data',
            9999
        );
        $hooks_service->add_filter(
            'preprocess_comment',
            $this,
            'filter_reply_data',
            9999
        );
        $hooks_service->add_filter(
            'rest_preprocess_comment',
            $this,
            'filter_reply_data',
            9999
        );
        $hooks_service->add_action( 'trashed_comment', $this, 'trash_ic_replies' );
        $hooks_service->add_action( 'untrashed_comment', $this, 'untrash_ic_replies' );
        $hooks_service->add_action( 'deleted_comment', $this, 'delete_ic_replies' );
    }
    
    // endregion
    // region HOOKS
    /**
     * When posting replies to DWS internal comments, force the replies to be internal comments themselves.
     *
     * @since   1.0.0
     * @version 1.1.0
     *
     * @param   array   $comment_data   The reply comment data.
     *
     * @return  array
     */
    public function filter_reply_data( array $comment_data ) : array
    {
        $comment_parent = $comment_data['comment_parent'] ?? null;
        if ( true === dws_ic_is_internal_comment( $comment_parent ) ) {
            $comment_data['comment_type'] = 'dws_internal';
        }
        return $comment_data;
    }
    
    /**
     * Automagically trash replies of trashed internal comments.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   int     $comment_id     The ID of the comment that was trashed.
     */
    public function trash_ic_replies( int $comment_id ) : void
    {
        $replies = $this->get_comment_children( $comment_id );
        foreach ( $replies as $reply_id ) {
            \wp_trash_comment( $reply_id );
        }
    }
    
    /**
     * Automagically untrash replies of untrashed internal comments.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   int     $comment_id     The ID of the comment that was untrashed.
     */
    public function untrash_ic_replies( int $comment_id ) : void
    {
        $replies = $this->get_comment_children( $comment_id );
        foreach ( $replies as $reply_id ) {
            \wp_untrash_comment( $reply_id );
        }
    }
    
    /**
     * Automagically delete replies of deleted internal comments.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   int     $comment_id     The ID of the comment that was deleted.
     */
    public function delete_ic_replies( int $comment_id ) : void
    {
        $replies = $this->get_comment_children( $comment_id );
        foreach ( $replies as $reply_id ) {
            \wp_delete_comment( $reply_id, true );
        }
    }
    
    // endregion
    // region HELPERS
    /**
     * Query the database for all children comments of a given comment ID.
     *
     * @since   1.0.0
     * @version 1.1.0
     *
     * @param   int     $comment_id     The ID of the parent comment.
     *
     * @return  array
     */
    protected function get_comment_children( int $comment_id ) : array
    {
        global  $wpdb ;
        if ( true !== dws_ic_is_internal_comment( $comment_id ) ) {
            return array();
        }
        return Arrays::validate(
            $wpdb->get_col( $wpdb->prepare( "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_parent = %d", $comment_id ) ),
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery
            array()
        );
    }

}