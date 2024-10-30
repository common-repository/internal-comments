<?php

namespace DeepWebSolutions\Plugins\InternalComments;

use  DeepWebSolutions\Plugins\InternalComments\Permissions\CommentPermissions ;
use  DeepWebSolutions\Plugins\InternalComments\Permissions\OutputPermissions ;
use  DeepWebSolutions\Plugins\InternalComments\Permissions\Premium\OutputPermissions as OutputPermissionsPremium ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\Functionalities\AbstractPermissionsFunctionality ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Actions\SetupHooksTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService ;
\defined( 'ABSPATH' ) || exit;
/**
 * Logical node for managing permissions collections.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class Permissions extends AbstractPermissionsFunctionality
{
    // region TRAITS
    use  SetupHooksTrait ;
    // endregion
    // region INHERITED METHODS
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    protected function get_di_container_children() : array
    {
        return array( Permissions\CommentPermissions::class, Permissions\OutputPermissions::class, Permissions\SettingsPermissions::class );
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function register_hooks( HooksService $hooks_service ) : void
    {
        $hooks_service->add_filter(
            'map_meta_cap',
            $this,
            'map_meta_cap',
            10,
            4
        );
    }
    
    // endregion
    // region HOOKS
    /**
     * Maps meta capabilities to primitive capabilities defined by this class.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @param   string[]    $caps       Primitive capabilities required of the user.
     * @param   string      $cap        Capability being checked.
     * @param   int         $user_id    The user ID.
     * @param   array       $args       Adds context to the capability check, typically starting with an object ID.
     *
     * @return  array
     */
    public function map_meta_cap(
        array $caps,
        string $cap,
        int $user_id,
        array $args
    ) : array
    {
        switch ( $cap ) {
            case 'edit_comment':
                if ( empty(dws_ic_is_internal_comment( $args[0] )) ) {
                    break;
                }
                $action = \apply_filters( $this->get_hook_tag( 'edit_comment_action' ), $GLOBALS['dws_ic_action'] ?? $GLOBALS['action'] ?? 'editcomment' );
                
                if ( \in_array( $action, array(
                    'edit',
                    'editcomment',
                    'edit-comment',
                    'editedcomment'
                ), true ) ) {
                    // phpcs:disable
                    unset( $_POST['comment_status'] );
                    // Disallow changing the status to anything but 'Approved'.
                    foreach ( array(
                        'aa',
                        'mm',
                        'jj',
                        'hh',
                        'mn'
                    ) as $timeunit ) {
                        unset( $_POST['hidden_' . $timeunit] );
                        // Disallow changing the date the comment was submitted on.
                    }
                    unset( $_POST['newcomment_author'] );
                    // Disallow changing the comment author.
                    unset( $_POST['newcomment_author_email'] );
                    // Disallow changing the comment author's email.
                    unset( $_POST['newcomment_author_url'] );
                    // Disallow changing the comment author's URL.
                    // phpcs:enable
                    $caps = \map_meta_cap( 'edit_dws_internal_comment', $user_id, ...$args );
                } elseif ( \in_array( $action, array(
                    'delete',
                    'deletecomment',
                    'delete-comment',
                    'trash',
                    'trashcomment',
                    'untrash',
                    'untrashcomment'
                ), true ) ) {
                    $caps = \map_meta_cap( 'trash_dws_internal_comment', $user_id, ...$args );
                } elseif ( 'dim-comment' === $action ) {
                    $_POST['new'] = \wp_get_comment_status( $args[0] );
                    // Prevent using the 'approve' / 'unapprove' mechanism.
                } else {
                    $caps[] = 'do_not_allow';
                }
                
                break;
            case 'see_dws_internal_comment':
            case 'edit_dws_internal_comment':
            case 'trash_dws_internal_comment':
                $comment = \get_comment( $args[0] );
                
                if ( empty(dws_ic_is_internal_comment( $comment )) ) {
                    $caps[] = 'do_not_allow';
                    break;
                }
                
                $dws_comment = new \DWS_Internal_Comment( $comment );
                
                if ( 'edit_dws_internal_comment' === $cap ) {
                    $caps = array( ( $dws_comment->is_author( $user_id ) ? CommentPermissions::EDIT_INTERNAL_COMMENTS : CommentPermissions::EDIT_OTHERS_INTERNAL_COMMENTS ) );
                } elseif ( 'trash_dws_internal_comment' === $cap ) {
                    $caps = array( ( $dws_comment->is_author( $user_id ) ? CommentPermissions::TRASH_INTERNAL_COMMENTS : CommentPermissions::TRASH_OTHERS_INTERNAL_COMMENTS ) );
                } elseif ( 'see_dws_internal_comment' === $cap ) {
                    $caps = ( $dws_comment->is_author( $user_id ) ? array() : array( OutputPermissions::SEE_INTERNAL_COMMENTS ) );
                }
                
                break;
        }
        return $caps;
    }

}