<?php

namespace DeepWebSolutions\Plugins\InternalComments\Output;

use  DeepWebSolutions\Plugins\InternalComments\Permissions\CommentPermissions ;
use  DeepWebSolutions\Plugins\InternalComments\Permissions\OutputPermissions ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputFailureException ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputLocalTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\OutputtableInterface ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\AssetsHelpersTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Activeable\ActiveLocalTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Assets ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Integers ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Users ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Actions\SetupSettingsTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsService ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Actions\SetupHooksTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService ;
\defined( 'ABSPATH' ) || exit;
/**
 * Outputs the internal comments added so far and a form to insert new ones on edit post screens.
 *
 * @since   1.0.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class MetaBox extends AbstractPluginFunctionality implements  OutputtableInterface 
{
    // region TRAITS
    use  AssetsHelpersTrait ;
    use  ActiveLocalTrait ;
    use  SetupHooksTrait ;
    use  SetupSettingsTrait ;
    use  OutputLocalTrait ;
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
        return Users::has_capabilities( OutputPermissions::SEE_METABOX ) ?? false;
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function register_hooks( HooksService $hooks_service ) : void
    {
        $hooks_service->add_action( 'admin_enqueue_scripts', $this, 'enqueue_admin_assets' );
        $hooks_service->add_action( 'wp_ajax_dws_ic_get_comments_list_output', $this, 'ajax_get_comments_list_output' );
        $hooks_service->add_action( 'admin_footer', $this, 'output_js_templates' );
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.1.2
     */
    public function register_settings( SettingsService $settings_service ) : void
    {
        $selected_post_types = dws_ic_get_selected_post_types();
        if ( empty($selected_post_types) ) {
            return;
        }
        $settings_service->register_generic_group(
            'dws-internal-comments',
            function () {
            return \__( 'Internal Comments', 'internal-comments' );
        },
            array(),
            $selected_post_types,
            array(
            'priority' => 'core',
            'callback' => array( $this, 'output' ),
        )
        );
    }
    
    // endregion
    // region HOOKS
    /**
     * Enqueues the scripts and styles required for the meta-box.
     *
     * @since   1.2.0
     * @version 1.2.0
     *
     * @param   string  $hook_suffix    The WordPress admin page hook.
     */
    public function enqueue_admin_assets( string $hook_suffix )
    {
        global  $post ;
        if ( !\in_array( $hook_suffix, array( 'post-new.php', 'post.php' ), true ) || true !== dws_ic_is_selected_post_type() ) {
            return;
        }
        $plugin = $this->get_plugin();
        $minified_path = Assets::maybe_get_minified_path( $plugin::get_plugin_assets_url() . 'dist/css/metabox.css' );
        \wp_enqueue_style(
            $this->get_asset_handle(),
            $minified_path,
            array(),
            Assets::maybe_get_mtime_version( $minified_path, $plugin->get_plugin_version() )
        );
        $minified_path = Assets::maybe_get_minified_path( $plugin::get_plugin_assets_url() . 'dist/js/metabox.js' );
        \wp_enqueue_script(
            $this->get_asset_handle(),
            $minified_path,
            array( dws_ic_component( 'ajax' )->get_asset_handle() ),
            Assets::maybe_get_mtime_version( $minified_path, $plugin->get_plugin_version() ),
            true
        );
        \wp_localize_script( $this->get_asset_handle(), 'dws_ic_metabox_vars', \apply_filters( $this->get_hook_tag( 'script_vars' ), array(
            'post_id'              => ( $post ? $post->ID : '' ),
            'empty_comment_msg'    => \__( 'You cannot post an empty internal comment.', 'internal-comments' ),
            'insert_comment_nonce' => \wp_create_nonce( 'dws_insert_internal_comment_' . (( $post ? $post->ID : '' )) ),
        ) ) );
    }
    
    /**
     * Returns the comments list output for a given post via AJAX.
     *
     * @since   1.0.0
     * @version 1.1.0
     */
    public function ajax_get_comments_list_output() : void
    {
        $post_id = Integers::maybe_cast_input( INPUT_GET, 'post_id' );
        if ( empty($post_id) || false === dws_ic_is_selected_post_type( $post_id ) ) {
            \wp_send_json_error( \__( 'Post ID is invalid.', 'internal-comments' ) );
        }
        \wp_send_json_success( $this->get_comments_list_output( $post_id ) );
    }
    
    /**
     * Outputs JS templates to be used for inline actions.
     *
     * @since   1.1.0
     * @version 1.2.0
     */
    public function output_js_templates() : void
    {
        if ( false === dws_ic_is_selected_post_type() ) {
            return;
        }
        dws_ic_include_view( 'html-ic-edit-form.php' );
        \do_action( $this->get_hook_tag( 'js_templates' ) );
    }
    
    // endregion
    // region OUTPUT
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    protected function output_local() : ?OutputFailureException
    {
        ?>

		<div class="dws-internal-comments">
			<?php 
        echo  $this->get_comments_list_output() ;
        // phpcs:ignore
        ?>
		</div>

		<?php 
        
        if ( Users::has_capabilities( CommentPermissions::INSERT_INTERNAL_COMMENTS ) ) {
            ?>

		<div class="dws-add-internal-comment dws-internal-comments-inline-action">
			<div class="dws-add-internal-comment__textarea dws-internal-comments-inline-action__textarea">
				<label for="new-internal-comment">
					<?php 
            \esc_html_e( 'New internal comment', 'internal-comments' );
            ?>
				</label>
				<textarea id="new-internal-comment" placeholder="<?php 
            \esc_attr_e( 'Write your comment here &hellip;', 'internal-comments' );
            ?>" rows="4"></textarea>
			</div>
			<div class="dws-add-internal-comment__actions dws-internal-comments-inline-action__actions">
				<?php 
            \do_action( $this->get_hook_tag( 'inline_actions' ) );
            ?>
				<?php 
            \do_action( $this->get_hook_tag( 'inline_actions', 'insert' ) );
            ?>
				<button type="button" class="save-insert-internal-comment button" data-action="add">
					<?php 
            \esc_html_e( 'Post comment', 'internal-comments' );
            ?>
				</button>
			</div>
		</div>
		<div class="dws-internal-comments-overlay" style="display: none;"></div>

			<?php 
        }
        
        return null;
    }
    
    /**
     * Returns the HTML output of all the internal comments for a given post.
     *
     * @since   1.0.0
     * @version 1.2.0
     *
     * @param   int|null    $post_id    The ID of the post to retrieve the HTML for.
     *
     * @return  string
     */
    protected function get_comments_list_output( ?int $post_id = null ) : string
    {
        $post_id = ( $post_id ?: \get_the_ID() );
        if ( empty($post_id) ) {
            return '';
        }
        $internal_comments = dws_ic_get_internal_comments( $post_id );
        \ob_start();
        dws_ic_include_view( 'html-comments-list.php', array(
            'internal_comments' => $internal_comments,
            'post_id'           => $post_id,
        ) );
        return \ob_get_clean();
    }

}