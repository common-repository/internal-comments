<?php

namespace DeepWebSolutions\Plugins\InternalComments\Permissions;

use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\Functionalities\AbstractPermissionsChildFunctionality ;
/**
 * Collection of permissions for CRUD operations.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class CommentPermissions extends AbstractPermissionsChildFunctionality
{
    // region PERMISSION CONSTANTS
    /**
     * Capability needed to be able to insert new internal comments.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @var     string
     */
    public const  INSERT_INTERNAL_COMMENTS = 'insert_dws_internal_comments' ;
    /**
     * Capability needed to be able to edit ones own internal comments.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @var     string
     */
    public const  EDIT_INTERNAL_COMMENTS = 'edit_dws_internal_comments' ;
    /**
     * Capability needed to be able to trash ones own internal comments.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @var     string
     */
    public const  EDIT_OTHERS_INTERNAL_COMMENTS = 'edit_others_dws_internal_comments' ;
    /**
     * Capability needed to be able to edit the internal comments of others.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @var     string
     */
    public const  TRASH_INTERNAL_COMMENTS = 'trash_dws_internal_comments' ;
    /**
     * Capability needed to be able to trash the internal comments of others.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @var     string
     */
    public const  TRASH_OTHERS_INTERNAL_COMMENTS = 'trash_others_dws_internal_comments' ;
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
        $children = array();
        return $children;
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function get_granting_rules() : array
    {
        $include_rule = array(
            'rule'        => 'include',
            'permissions' => array( self::INSERT_INTERNAL_COMMENTS ),
        );
        return array(
            'administrator' => 'all',
            'shop_manager'  => $include_rule,
            'editor'        => $include_rule,
            'author'        => $include_rule,
            'contributor'   => $include_rule,
        );
    }

}