<?php

namespace DeepWebSolutions\Plugins\InternalComments\Permissions;

use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\Functionalities\AbstractPermissionsChildFunctionality ;
/**
 * Collection of permissions used by the output portion of the plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class OutputPermissions extends AbstractPermissionsChildFunctionality
{
    // region PERMISSION CONSTANTS
    /**
     * Capability needed to be able to see the internal comments output at all.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @var     string
     */
    public const  SEE_INTERNAL_COMMENTS = 'see_dws_internal_comments' ;
    /**
     * Capability needed to be able to see the internal comments WP table.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @var     string
     */
    public const  SEE_INTERNAL_COMMENTS_TABLE = 'see_dws_internal_comments_table' ;
    /**
     * Capability needed to be able to see the internal comments metabox.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @var     string
     */
    public const  SEE_METABOX = 'see_dws_internal_comments_metabox' ;
    /**
     * Capability needed to be able to see the internal comments table column.
     *
     * @since   1.0.0
     * @version 1.0.0
     *
     * @var     string
     */
    public const  SEE_TABLE_COLUMN = 'see_dws_internal_comments_column' ;
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
        $exclude_rule = array(
            'rule'        => 'exclude',
            'permissions' => array( self::SEE_INTERNAL_COMMENTS_TABLE ),
        );
        return array(
            'administrator' => 'all',
            'shop_manager'  => $exclude_rule,
            'editor'        => $exclude_rule,
            'author'        => $exclude_rule,
            'contributor'   => $exclude_rule,
        );
    }

}