<?php

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Integers;

defined( 'ABSPATH' ) || exit;

/**
 * Converts a WP comment reference to a WP_Comment object.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @param   WP_Comment|string|int|array     $comment    Comment to retrieve.
 *
 * @return  WP_Comment|null
 */
function dws_ic_get_comment( $comment ): ?WP_Comment {
	$comment = is_array( $comment ) && isset( $comment['comment_ID'] ) ? $comment['comment_ID'] : $comment;
	$comment = get_comment( $comment );

	return is_a( $comment, 'WP_Comment' ) ? $comment : null;
}

/**
 * Determines whether a given comment is a DWS internal comment or not.
 *
 * @since   1.0.0
 * @version 1.1.0
 *
 * @param   WP_Comment|string|int|array     $comment    Comment to retrieve.
 *
 * @return  bool|null
 */
function dws_ic_is_internal_comment( $comment ): ?bool {
	if ( empty( $comment ) ) {
		return null;
	}

	$comment = dws_ic_get_comment( $comment );
	return is_null( $comment ) ? null
		: 'dws_internal' === $comment->comment_type;
}

/**
 * Returns whether a given post or the global $post are of the type for which internal comments are enabled.
 *
 * @since   1.1.0
 * @version 1.1.0
 *
 * @param   int|null    $post_id    Post ID to check. Default is global $post.
 *
 * @return  bool
 */
function dws_ic_is_selected_post_type( ?int $post_id = null ): bool {
	return \in_array( \get_post_type( $post_id ), dws_ic_get_selected_post_types(), true );
}

/**
 * Inserts a new comment into the database.
 *
 * @since   1.1.0
 * @version 1.1.0
 *
 * @param   array           $args       Additional comment arguments.
 * @param   WP_user|null    $author     The author of the comment.
 *
 * @return int|null
 */
function dws_ic_create_internal_comment( array $args = array(), ?\WP_User $author = null ): ?int {
	$author = $author ?: wp_get_current_user();
	$args   = wp_parse_args(
		$args,
		array(
			'user_id'              => $author->ID,
			'comment_author'       => $author->display_name,
			'comment_author_email' => $author->user_email,
			'comment_author_url'   => $author->user_url,

			'comment_content'      => '',
			'comment_post_ID'      => get_the_ID(),
			'comment_parent'       => 0,

			'comment_meta'         => array(),
		)
	);

	$comment_data = array(
		'comment_type'     => 'dws_internal',
		'comment_approved' => 1,
	) + $args;
	$comment_data = \apply_filters( dws_ic_get_hook_tag( 'create_comment_data' ), $comment_data );

	$result = wp_insert_comment( $comment_data );
	return Integers::maybe_cast( $result );
}

/**
 * Helper for retrieving the internal comments for a given post ID.
 *
 * @since   1.0.0
 * @version 1.1.0
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 *
 * @param   int     $post_id            The ID of the post to retrieve the internal comments for.
 * @param   array   $args               Optional arguments to pass on to @get_comments.
 * @param   bool    $include_hidden     Whether to include posts that the currently logged in user isn't allowed to see.
 *
 * @return  DWS_Internal_Comment[]
 */
function dws_ic_get_internal_comments( int $post_id, array $args = array(), bool $include_hidden = false ): array {
	$args = array(
		'post_id'      => $post_id,
		'type'         => 'dws_internal',
		'cache_domain' => 'dws-internal-comments',
	) + array_merge(
		array(
			'status'       => 'approve',
			'hierarchical' => 'threaded',
		),
		$args
	);

	// The 'count' and 'fields' args are not supported.
	unset( $args['count'], $args['fields'] );

	// Perform comments query.
	do_action( dws_ic_get_hook_tag( 'comments', array( 'before_get_query' ) ) );
	$comments = get_comments( $args );
	do_action( dws_ic_get_hook_tag( 'comments', array( 'after_get_query' ) ) );

	return array_filter(
		array_map(
			function( WP_Comment $comment ) use ( $include_hidden ) {
				$comment = new DWS_Internal_Comment( $comment );
				return $include_hidden || $comment->can_see() ? $comment : false;
			},
			$comments
		)
	);
}

/**
 * Helper for retrieving the internal comments from the trash meta of a trashed post.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 *
 * @param   int     $post_id            The ID of the trashed post to retrieve the internal comments for.
 * @param   string  $status             The status of the comments to retrieve. Use 'any' to include all.
 * @param   bool    $include_hidden     Whether to include posts that the currently logged in user isn't allowed to see.
 *
 * @return  array
 */
function dws_ic_get_internal_comments_from_trash_meta( int $post_id, string $status = '1', bool $include_hidden = false ): array {
	$statuses = Arrays::validate( get_post_meta( $post_id, '_wp_trash_meta_comments_status', true ), array() );
	$statuses = array_filter(
		$statuses,
		function( string $value ) use ( $status ) {
			return 'any' === $status || $status === $value;
		}
	);

	return array_filter(
		array_map(
			function( int $comment_id ) use ( $include_hidden ) {
				try {
					$comment = new DWS_Internal_Comment( $comment_id );
					return $include_hidden || $comment->can_see() ? $comment : false;
				} catch ( Exception $exception ) {
					return false;
				}
			},
			array_keys( $statuses )
		)
	);
}
