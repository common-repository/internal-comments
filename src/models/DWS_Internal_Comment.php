<?php

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotSupportedException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Integers;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Users;

defined( 'ABSPATH' ) || exit;

/**
 * Class modelling an internal comment's properties.
 *
 * @since   1.0.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class DWS_Internal_Comment {
	// region FIELDS AND CONSTANTS

	/**
	 * WordPress comment object storing the current internal comment.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     WP_Comment
	 */
	protected WP_Comment $wp_comment;

	// endregion

	// region MAGIC METHODS

	/**
	 * DWS_Internal_Comment constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   WP_Comment|string|int|array     $comment        WordPress comment ID or comment object.
	 *
	 * @throws  NotSupportedException   Thrown if the comment passed on is not of the proper type.
	 */
	public function __construct( $comment ) {
		$comment = dws_ic_get_comment( $comment );
		if ( true !== dws_ic_is_internal_comment( $comment ) ) {
			throw new NotSupportedException( 'Comment type is unsupported' );
		}

		$this->wp_comment = $comment;
	}

	/**
	 * Transparently access the WP Comment data.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $name   Name of the WP Comment property to return.
	 *
	 * @return  mixed|null
	 */
	public function __get( string $name ) {
		return property_exists( $this->wp_comment, $name )
			? $this->wp_comment->{$name} : null;
	}

	/**
	 * Transparently call the WP Comment methods.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $name   Name of the WP Comment method to call.
	 * @param   array   $args   Arguments to pass on to the called method.
	 *
	 * @return  null|mixed
	 */
	public function __call( string $name, array $args ) {
		return method_exists( $this->wp_comment, $name )
			? call_user_func_array( array( $this->wp_comment, $name ), $args ) : null;
	}

	// endregion

	// region METHODS

	/**
	 * Checks whether a given user is the comment's author.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 *
	 * @param   int|null    $user_id    The ID of the user to check authorship for. Defaults to the currently logged in user.
	 *
	 * @return  bool
	 */
	public function is_author( ?int $user_id = null ): bool {
		$user = Users::get( $user_id );
		return Integers::maybe_cast( $this->wp_comment->user_id ) === ( is_null( $user ) ? 0 : $user->ID );
	}

	/**
	 * Checks whether a given user can edit the comment's author's profile.
	 *
	 * @since   1.0.0
	 * @version 1.2.0
	 *
	 * @param   int|null    $user_id    The ID of the WP user to check the capabilities for. Defaults to the currently logged in user.
	 *
	 * @return  bool
	 */
	public function can_edit_author( ?int $user_id = null ): bool {
		return Users::has_capabilities( 'edit_user', array( $this->wp_comment->user_id ), $user_id ) ?? false;
	}

	/**
	 * Checks whether a given user can see the comment.
	 *
	 * @since   1.0.0
	 * @version 1.2.0
	 *
	 * @param   int|null    $user_id    The ID of the WP user to check the capabilities for. Defaults to the currently logged in user.
	 *
	 * @return  bool
	 */
	public function can_see( ?int $user_id = null ): bool {
		return Users::has_capabilities( 'see_dws_internal_comment', array( $this->wp_comment->comment_ID ), $user_id ) ?? false;
	}

	/**
	 * Checks whether a given user can edit the comment.
	 *
	 * @since   1.0.0
	 * @version 1.2.0
	 *
	 * @param   int|null    $user_id    The ID of the WP user to check the capabilities for. Defaults to the currently logged in user.
	 *
	 * @return  bool
	 */
	public function can_edit( ?int $user_id = null ): bool {
		return Users::has_capabilities( 'edit_dws_internal_comment', array( $this->wp_comment->comment_ID ), $user_id ) ?? false;
	}

	/**
	 * Wrapper around WP's own @get_edit_comment_link function for AJAX compatibility with the capabilities check.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 *
	 * @return  string|null
	 */
	public function get_edit_comment_link(): ?string {
		global $dws_ic_action;

		$dws_ic_action = 'edit';
		$comment_link  = get_edit_comment_link( $this->wp_comment->comment_ID );

		unset( $dws_ic_action );

		return Strings::validate( $comment_link );
	}

	/**
	 * Checks whether a given user can trash the comment.
	 *
	 * @since   1.0.0
	 * @version 1.2.0
	 *
	 * @param   int|null    $user_id    The ID of the WP user to check the capabilities for. Defaults to the currently logged in user.
	 *
	 * @return  bool
	 */
	public function can_trash( ?int $user_id = null ): bool {
		return Users::has_capabilities( 'trash_dws_internal_comment', array( $this->wp_comment->comment_ID ), $user_id ) ?? false;
	}

	// endregion
}
