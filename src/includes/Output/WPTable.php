<?php

namespace DeepWebSolutions\Plugins\InternalComments\Output;

use  DeepWebSolutions\Plugins\InternalComments\Permissions\OutputPermissions ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\AssetsHelpersTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Activeable\ActiveLocalTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Assets ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Request ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Users ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Actions\SetupHooksTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService ;
\defined( 'ABSPATH' ) || exit;
/**
 * Outputs the internal comments added so far to a new column inside WP Tables.
 *
 * @since   1.0.0
 * @version 1.2.3
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class WPTable extends AbstractPluginFunctionality
{
    // region TRAITS
    use  ActiveLocalTrait ;
    use  AssetsHelpersTrait ;
    use  SetupHooksTrait ;
    // endregion
    // region INHERITED METHODS
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.2.0
     */
    public function is_active_local() : bool
    {
        return Users::has_capabilities( OutputPermissions::SEE_TABLE_COLUMN ) ?? false;
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.2.0
     */
    public function register_hooks( HooksService $hooks_service ) : void
    {
        foreach ( dws_ic_get_selected_post_types() as $post_type ) {
            $hooks_service->add_filter( "manage_edit-{$post_type}_columns", $this, 'register_column' );
            $hooks_service->add_action(
                "manage_{$post_type}_posts_custom_column",
                $this,
                'output_column',
                10,
                2
            );
        }
        $hooks_service->add_action( 'admin_print_styles-edit.php', $this, 'enqueue_admin_styles' );
        $hooks_service->add_action( 'admin_footer', $this, 'output_js_templates' );
    }
    
    // endregion
    // region HOOKS
    /**
     * Register the internal comments column with the relevant WP tables.
     *
     * @since   1.0.0
     * @version 1.2.3
     *
     * @param   array   $columns    Columns registered with the WP table.
     *
     * @return  array
     */
    public function register_column( array $columns ) : array
    {
        $post_type = null;
        $insert_after = ( isset( $columns['comments'] ) ? 'comments' : 'title' );
        $current_screen = \get_current_screen();
        
        if ( $current_screen instanceof \WP_Screen ) {
            $post_type = $current_screen->post_type;
        } elseif ( Strings::validate( $_POST['screen'] ?? null ) && Request::is_type( 'ajax' ) ) {
            // phpcs:ignore WordPress.Security
            $current_screen = \convert_to_screen( $_POST['screen'] );
            // phpcs:ignore WordPress.Security
            $post_type = ( \post_type_exists( $current_screen->post_type ) ? $current_screen->post_type : null );
        }
        
        $insert_after = \apply_filters( $this->get_hook_tag( 'insert_after' ), $insert_after, $post_type );
        return Arrays::insert_after( $columns, $insert_after, array(
            'dws-internal-comments' => \__( 'Internal Comments', 'internal-comments' ),
        ) );
    }
    
    /**
     * Outputs the content of the internal comments column.
     *
     * @since   1.0.0
     * @version 1.1.0
     *
     * @param   string  $column_name    The name of the table column being rendered.
     * @param   int     $post_id        The ID of the post of the table row being rendered.
     */
    public function output_column( string $column_name, int $post_id ) : void
    {
        if ( 'dws-internal-comments' !== $column_name ) {
            return;
        }
        ?>

		<div class="post-com-count-wrapper">
			<?php 
        
        if ( 'trash' === \get_post_status( $post_id ) ) {
            $internal_comments = dws_ic_get_internal_comments_from_trash_meta( $post_id );
        } else {
            $internal_comments = dws_ic_get_internal_comments( $post_id, array(
                'hierarchical' => false,
            ) );
        }
        
        
        if ( empty($internal_comments) ) {
            \printf( '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">%s</span>', \esc_html__( 'No internal comments', 'internal-comments' ) );
        } else {
            $comments_number = \count( $internal_comments );
            $screen_reader_text = \sprintf(
                /* translators: %s: Number of comments. */
                \_n(
                    '%s internal comment',
                    '%s internal comments',
                    $comments_number,
                    'internal-comments'
                ),
                \number_format_i18n( $comments_number )
            );
            
            if ( 'trash' === \get_post_status( $post_id ) || !Users::has_capabilities( OutputPermissions::SEE_INTERNAL_COMMENTS_TABLE ) ) {
                \printf(
                    '<span class="dws-internal-comments post-com-count" data-post-id="%d"><span class="comment-count" aria-hidden="true">%s</span><span class="screen-reader-text">%s</span></span>',
                    \esc_attr( $post_id ),
                    \esc_html( \number_format_i18n( $comments_number ) ),
                    \esc_html( $screen_reader_text )
                );
            } else {
                \printf(
                    '<a href="%s" target="_blank" class="dws-internal-comments post-com-count" data-post-id="%s"><span class="comment-count" aria-hidden="true">%s</span><span class="screen-reader-text">%s</span></a>',
                    \esc_url( \add_query_arg( array(
                    'p'            => $post_id,
                    'comment_type' => 'dws_internal',
                ), \admin_url( 'edit-comments.php' ) ) ),
                    \esc_attr( $post_id ),
                    \esc_html( \number_format_i18n( $comments_number ) ),
                    \esc_html( $screen_reader_text )
                );
            }
        
        }
        
        ?>
		</div>

		<?php 
    }
    
    /**
     * Enqueues the admin styles for the column.
     *
     * @since   1.2.0
     * @version 1.2.0
     */
    public function enqueue_admin_styles()
    {
        
        if ( true === dws_ic_is_selected_post_type() ) {
            $plugin = $this->get_plugin();
            $minified_path = Assets::maybe_get_minified_path( $plugin::get_plugin_assets_url() . 'dist/css/edit-posts.css' );
            \wp_enqueue_style(
                $this->get_asset_handle(),
                $minified_path,
                array(),
                Assets::maybe_get_mtime_version( $minified_path, $plugin->get_plugin_version() )
            );
        }
    
    }
    
    /**
     * Outputs JS templates related to the new column.
     *
     * @since   1.1.0
     * @version 1.1.0
     */
    public function output_js_templates() : void
    {
        if ( false === dws_ic_is_selected_post_type() ) {
            return;
        }
        \do_action( $this->get_hook_tag( 'js_templates' ) );
    }

}