<?php

namespace DeepWebSolutions\Plugins\InternalComments;

use DeepWebSolutions\Plugins\InternalComments\Permissions\CommentPermissions;
use DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\AssetsHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Assets;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Integers;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Users;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Actions\SetupHooksTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService;

\defined( 'ABSPATH' ) || exit;

/**
 * Registers AJAX operations for interacting with internal comments.
 *
 * @since   1.0.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class Ajax extends AbstractPluginFunctionality {
	// region TRAITS

	use AssetsHelpersTrait;
	use SetupHooksTrait;

	// endregion

	// region INHERITED METHODS

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.2.0
	 */
	public function register_hooks( HooksService $hooks_service ): void {
		$hooks_service->add_action( 'wp_ajax_dws_ic_insert_comment', $this, 'insert_internal_comment' );
		$hooks_service->add_action( 'wp_ajax_dws_ic_get_comments', $this, 'get_internal_comments' );
		$hooks_service->add_action( 'wp_ajax_dws_ic_get_comment', $this, 'get_internal_comment' );
		$hooks_service->add_action( 'wp_ajax_dws_ic_update_comment', $this, 'update_internal_comment' );
		$hooks_service->add_action( 'wp_ajax_dws_ic_trash_comment', $this, 'trash_internal_comment' );

		$hooks_service->add_action( 'admin_enqueue_scripts', $this, 'register_admin_scripts' );
		$hooks_service->add_filter( dws_ic_get_component_hook_tag( 'permissions', 'edit_comment_action' ), $this, 'filter_edit_comment_action' );
	}

	// endregion

	// region AJAX

	/**
	 * Adds new comments received via AJAX to the database.
	 *
	 * @since   1.0.0
	 * @version 1.2.0
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function insert_internal_comment(): void {
		$post_id   = Integers::maybe_cast_input( INPUT_POST, 'post_id', 0 );
		$parent_id = Integers::maybe_cast_input( INPUT_POST, 'parent_id', 0 );

		if ( false === \check_ajax_referer( "dws_insert_internal_comment_$post_id", false, false ) && false === \check_ajax_referer( "dws_reply_to_internal_comment_$parent_id", false, false ) ) {
			\wp_send_json_error( \__( 'Your session has expired. Please reload the page and try again!', 'internal-comments' ) );
		} elseif ( empty( $post_id ) || false === dws_ic_is_selected_post_type( $post_id ) ) {
			\wp_send_json_error( \__( 'Post ID is invalid.', 'internal-comments' ) );
		} elseif ( $parent_id > 0 && true !== dws_ic_is_internal_comment( $parent_id ) ) {
			\wp_send_json_error( \__( 'Invalid parent comment ID', 'internal-comments' ) );
		}

		$content = sanitize_textarea_field( Strings::maybe_cast_input( INPUT_POST, 'content' ) );
		if ( empty( $content ) ) {
			\wp_send_json_error( \__( 'Comment text is empty.', 'internal-comments' ) );
		}

		$wp_user = \wp_get_current_user();
		if ( ! Users::has_capabilities( CommentPermissions::INSERT_INTERNAL_COMMENTS, array(), $wp_user->ID ) ) {
			\wp_send_json_error( \__( 'You don\'t have the necessary permissions to add this comment.', 'internal-comments' ) );
		}

		\do_action( $this->get_hook_tag( 'before_insert_comment' ), $post_id );

		$result = dws_ic_create_internal_comment(
			array(
				'comment_content' => $content,
				'comment_post_ID' => $post_id,
				'comment_parent'  => $parent_id,
			),
			$wp_user
		);
		is_null( $result )
			? \wp_send_json_error( \__( 'Failed to insert comment into the database. Please try again!', 'internal-comments' ) )
			: \wp_send_json_success( \get_comment( $result, ARRAY_A ) );
	}

	/**
	 * Returns a list of internal comments for a given post ID.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 */
	public function get_internal_comments(): void {
		if ( false === \check_ajax_referer( 'dws_get_internal_comments', false, false ) ) {
			\wp_send_json_error( \__( 'Your session has expired. Please reload the page and try again!', 'internal-comments' ) );
		}

		$post_id = Integers::maybe_cast_input( INPUT_GET, 'post_id', 0 );
		if ( empty( $post_id ) || false === dws_ic_is_selected_post_type( $post_id ) ) {
			\wp_send_json_error( \__( 'Post ID is invalid.', 'internal-comments' ) );
		}

		\wp_send_json_success(
			\array_map(
				function( \DWS_Internal_Comment $comment ) {
					return $comment->to_array();
				},
				dws_ic_get_internal_comments( $post_id )
			)
		);
	}

	/**
	 * Returns an internal comment by its ID.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 */
	public function get_internal_comment(): void {
		if ( false === \check_ajax_referer( 'dws_get_internal_comments', false, false ) ) {
			\wp_send_json_error( \__( 'Your session has expired. Please reload the page and try again!', 'internal-comments' ) );
		}

		$comment_id = Integers::maybe_cast_input( INPUT_GET, 'comment_id', 0 );
		if ( ! dws_ic_is_internal_comment( $comment_id ) ) {
			\wp_send_json_error( \__( 'Invalid comment ID.', 'internal-comments' ) );
		}

		$comment     = \get_comment( $comment_id, ARRAY_A );
		$dws_comment = new \DWS_Internal_Comment( $comment );

		$dws_comment->can_see()
			? \wp_send_json_success( $comment )
			: \wp_send_json_error( \__( 'You don\'t have the necessary permissions to view this comment.', 'internal-comments' ) );
	}

	/**
	 * Updates an existing internal comment by its ID.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 */
	public function update_internal_comment(): void {
		$comment_id = Integers::maybe_cast_input( INPUT_POST, 'comment_id', 0 );
		if ( false === \check_ajax_referer( "dws_edit_internal_comment_$comment_id", false, false ) ) {
			\wp_send_json_error( \__( 'Your session has expired. Please reload the page and try again!', 'internal-comments' ) );
		} elseif ( ! dws_ic_is_internal_comment( $comment_id ) ) {
			\wp_send_json_error( \__( 'Invalid comment ID.', 'internal-comments' ) );
		}

		$comment     = \get_comment( $comment_id, ARRAY_A );
		$dws_comment = new \DWS_Internal_Comment( $comment );
		if ( false === $dws_comment->can_edit() ) {
			\wp_send_json_error( \__( 'You don\'t have the necessary permissions to edit this comment.', 'internal-comments' ) );
		}

		$content = sanitize_textarea_field( Strings::maybe_cast_input( INPUT_POST, 'content' ) );
		$content = empty( $content ) ? $comment['comment_content'] : $content;

		$comment_data  = array(
			'comment_ID'      => $comment_id,
			'comment_content' => $content,
		);
		$comment_data += \apply_filters( $this->get_hook_tag( 'update_internal_comment_data' ), array(), $comment_data );

		\do_action( $this->get_hook_tag( 'before_update_comment' ), $comment, $comment_data );

		( false === \wp_update_comment( $comment_data ) )
			? \wp_send_json_error( \__( 'Failed to update comment. Please try again!', 'internal-comments' ) )
			: \wp_send_json_success( $comment );
	}

	/**
	 * Moves comments to trash via AJAX.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 */
	public function trash_internal_comment(): void {
		$comment_id = Integers::maybe_cast_input( INPUT_POST, 'comment_id', 0 );
		if ( false === \check_ajax_referer( "dws_trash_internal_comment_$comment_id", false, false ) ) {
			\wp_send_json_error( \__( 'Your session has expired. Please reload the page and try again!', 'internal-comments' ) );
		} elseif ( ! dws_ic_is_internal_comment( $comment_id ) ) {
			\wp_send_json_error( \__( 'Invalid comment ID.', 'internal-comments' ) );
		}

		$comment     = \get_comment( $comment_id, ARRAY_A );
		$dws_comment = new \DWS_Internal_Comment( $comment_id );
		if ( false === $dws_comment->can_trash() ) {
			\wp_send_json_error( \__( 'You don\'t have the necessary permissions to trash this comment.', 'internal-comments' ) );
		}

		\do_action( $this->get_hook_tag( 'before_trash_comment' ), $comment );

		( false === \wp_trash_comment( $comment_id ) )
			? \wp_send_json_error( \__( 'Failed to trash comment. Please try again!', 'internal-comments' ) )
			: \wp_send_json_success( \array_merge( $comment, array( 'comment_approved' => EMPTY_TRASH_DAYS ? 'deleted' : 'trash' ) ) );
	}

	// endregion

	// region HOOKS

	/**
	 * Registers the admin AJAX actions script.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 */
	public function register_admin_scripts() {
		$plugin        = $this->get_plugin();
		$minified_path = Assets::maybe_get_minified_path( $plugin::get_plugin_assets_url() . 'dist/js/ajax.js' );
		\wp_register_script(
			$this->get_asset_handle(),
			$minified_path,
			array( 'jquery', 'wp-hooks' ),
			Assets::maybe_get_mtime_version( $minified_path, $plugin->get_plugin_version() ),
			true
		);
		\wp_localize_script(
			$this->get_asset_handle(),
			'dws_ic_ajax_vars',
			\apply_filters(
				$this->get_hook_tag( 'script_vars' ),
				array(
					/* translators: Error message provided by JavaScript. */
					'ajax_fail_msg'      => \__( 'Request failed with the following message: %error_msg%. Please try again!', 'internal-comments' ),
					'get_comments_nonce' => \wp_create_nonce( 'dws_get_internal_comments' ),
				)
			)
		);
	}

	/**
	 * Translates AJAX action names into CRUD actions recognized by the permissions checking class.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $action     The current recognized action.
	 *
	 * @return  string
	 */
	public function filter_edit_comment_action( string $action ): string {
		switch ( $action ) {
			case 'dws_ic_insert_comment':
			case 'dws_ic_update_comment':
				$action = 'edit';
				break;
			case 'dws_ic_trash_comment':
				$action = 'trash';
				break;
		}

		return $action;
	}

	// endregion
}
