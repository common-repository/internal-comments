<?php

namespace DeepWebSolutions\Plugins\InternalComments\Comments;

use DeepWebSolutions\Plugins\InternalComments\Permissions\OutputPermissions;
use DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\AssetsHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Activeable\ActiveLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Assets;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Users;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Actions\SetupSettingsTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Actions\SetupHooksTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService;

\defined( 'ABSPATH' ) || exit;

/**
 * Handles customization of the comments table.
 *
 * @since   1.0.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class ListTable extends AbstractPluginFunctionality {
	// region TRAITS

	use ActiveLocalTrait;
	use AssetsHelpersTrait;
	use SetupHooksTrait;
	use SetupSettingsTrait;

	// endregion

	// region INHERITED METHODS

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.2.0
	 */
	public function is_active_local(): bool {
		return Users::has_capabilities( OutputPermissions::SEE_INTERNAL_COMMENTS_TABLE ) ?? false;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.2.0
	 */
	public function register_hooks( HooksService $hooks_service ): void {
		$hooks_service->add_action( 'init', $this, 'maybe_allow_querying' );
		$hooks_service->add_action( 'admin_print_styles-edit-comments.php', $this, 'enqueue_admin_styles' );

		$hooks_service->add_filter( 'admin_comment_types_dropdown', $this, 'register_comment_type' );

		$hooks_service->add_filter( 'comment_row_actions', $this, 'remove_unsupported_row_actions', 9999, 2 );
		$hooks_service->add_filter( 'bulk_actions-edit-comments', $this, 'remove_unsupported_bulk_actions', 9999 );

		$hooks_service->add_filter( 'comment_edit_redirect', $this, 'maybe_filter_comment_edit_redirect', 9999, 2 );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_settings( SettingsService $settings_service ): void {
		$settings_service->register_submenu_page(
			'edit-comments.php',
			'',
			\__( 'Internal Comments', 'internal-comments' ),
			'edit-comments.php?comment_type=dws_internal',
			OutputPermissions::SEE_INTERNAL_COMMENTS_TABLE
		);
	}

	// endregion

	// region HOOKS

	/**
	 * Maybe enables internal comments querying on the appropriate screen.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 */
	public function maybe_allow_querying() {
		$comment_type_filter = Strings::maybe_cast_input( INPUT_GET, 'comment_type' );
		if ( 'dws_internal' === $comment_type_filter && 'edit-comments.php' === $GLOBALS['pagenow'] ) {
			\do_action( dws_ic_get_hook_tag( 'comments', array( 'before_get_query' ) ) );
		}
	}

	/**
	 * Enqueues the list-table specific styles.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 */
	public function enqueue_admin_styles() {
		if ( 'dws_internal' === Strings::maybe_cast_input( INPUT_GET, 'comment_type' ) ) {
			$plugin        = $this->get_plugin();
			$minified_path = Assets::maybe_get_minified_path( $plugin::get_plugin_assets_url() . 'dist/css/list-table.css' );
			\wp_enqueue_style(
				$this->get_asset_handle(),
				$minified_path,
				array(),
				Assets::maybe_get_mtime_version( $minified_path, $plugin->get_plugin_version() )
			);
		}
	}

	/**
	 * Registers the internal comments comment type with the comments table filters.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $comment_types      An array of comment types.
	 *
	 * @return  array
	 */
	public function register_comment_type( array $comment_types ): array {
		$comment_types['dws_internal'] = \__( 'Internal Comments', 'internal-comments' );
		return $comment_types;
	}

	/**
	 * Removes unsupported row actions from internal comments.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 *
	 * @param   array           $actions    An array of the available row actions.
	 * @param   \WP_Comment     $comment    The comment that the row belongs to.
	 *
	 * @return  array
	 */
	public function remove_unsupported_row_actions( array $actions, \WP_Comment $comment ): array {
		if ( dws_ic_is_internal_comment( $comment ) ) {
			foreach ( \array_keys( $actions ) as $key ) {
				if ( ! \in_array( $key, array( 'reply', 'quickedit', 'edit', 'trash', 'untrash', 'delete' ), true ) ) {
					unset( $actions[ $key ] );
				}
			}
		}

		return $actions;
	}

	/**
	 * Removes unsupported bulk actions when viewing internal comments.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 *
	 * @param   array   $actions    An array of the available bulk actions.
	 *
	 * @return  array
	 */
	public function remove_unsupported_bulk_actions( array $actions ): array {
		if ( 'dws_internal' === Strings::maybe_cast_input( INPUT_GET, 'comment_type' ) ) {
			foreach ( \array_keys( $actions ) as $key ) {
				if ( ! \in_array( $key, array( 'trash', 'untrash', 'delete' ), true ) ) {
					unset( $actions[ $key ] );
				}
			}
		}

		return $actions;
	}

	/**
	 * Changes the redirection target after editing a comment to the dedicated internal comments view.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $location       Location to redirect to.
	 * @param   int     $comment_id     The ID of the comment that was just edited.
	 *
	 * @return  string
	 */
	public function maybe_filter_comment_edit_redirect( string $location, int $comment_id ): string {
		return empty( $_POST['referredby'] ) && dws_ic_is_internal_comment( $comment_id ) // phpcs:ignore
			? \add_query_arg( 'comment_type', 'dws_internal', $location )
			: $location;
	}

	// endregion
}
