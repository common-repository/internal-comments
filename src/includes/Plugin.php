<?php

namespace DeepWebSolutions\Plugins\InternalComments;

use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\Actions\Installable\UninstallFailureException ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionalityRoot ;
\defined( 'ABSPATH' ) || exit;
/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
final class Plugin extends AbstractPluginFunctionalityRoot
{
    // region INHERITED METHODS
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    protected function get_di_container_children() : array
    {
        return \array_merge( parent::get_di_container_children(), array(
            Ajax::class,
            Comments::class,
            Output::class,
            Permissions::class,
            Query::class,
            Settings::class
        ) );
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.1.0
     * @version 1.1.0
     */
    public function uninstall() : ?UninstallFailureException
    {
        if ( true === dws_ic_get_validated_setting( 'remove-data-uninstall', 'plugin' ) ) {
            return parent::uninstall();
        }
        return null;
    }
    
    // endregion
    // region HOOKS
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function register_plugin_actions(
        array $actions,
        string $plugin_file,
        array $plugin_data,
        string $context
    ) : array
    {
        $action_links = array(
            'settings' => '<a href="' . dws_ic_fs_settings_url() . '" aria-label="' . \esc_attr__( 'View settings', 'internal-comments' ) . '">' . \esc_html__( 'Settings', 'internal-comments' ) . '</a>',
        );
        if ( !dws_ic_fs()->is_premium() || !(dws_ic_fs()->is_activation_mode() || dws_ic_fs()->can_use_premium_code()) ) {
            $action_links['upgrade'] = '<a href="' . \esc_url( dws_ic_fs()->get_upgrade_url() ) . '" aria-label="' . \esc_attr__( 'Upgrade for premium features', 'internal-comments' ) . '">' . \esc_html__( 'Upgrade', 'internal-comments' ) . '</a>';
        }
        return \array_merge( $action_links, $actions );
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.2.0
     */
    public function register_plugin_row_meta(
        array $plugin_meta,
        string $plugin_file,
        array $plugin_data,
        string $status
    ) : array
    {
        if ( $this->get_plugin_basename() !== $plugin_file ) {
            return $plugin_meta;
        }
        $row_meta = array(
            'support' => '<a href="' . \esc_url( dws_ic_fs()->get_support_forum_url() ) . '" aria-label="' . \esc_attr__( 'Visit community forums', 'internal-comments' ) . '">' . \esc_html__( 'Community support', 'internal-comments' ) . '</a>',
        );
        return \array_merge( $plugin_meta, $row_meta );
    }

}