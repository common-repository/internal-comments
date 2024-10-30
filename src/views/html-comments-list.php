<?php
/**
 * Admin view: comments list output.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @package DeepWebSolutions\WP-Plugins\InternalComments
 *
 * @var     DWS_Internal_Comment[]      $internal_comments      The comments being outputted.
 * @var     int                         $post_id                The ID of the post that the comments belong to.
 */

use DeepWebSolutions\Plugins\InternalComments\Permissions\CommentPermissions;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Users;

defined( 'ABSPATH' ) || exit;

if ( empty( $internal_comments ) ) : ?>

	<div class="dws-internal-comments__empty">
		<?php
		echo esc_html(
			wp_sprintf(
				/* translators: Post type singular name. */
				__( 'There are no internal comments on this %s yet.', 'internal-comments' ),
				get_post_type_object( get_post_type( $post_id ) )->labels->singular_name
			)
		);
		?>

		<?php if ( Users::has_capabilities( CommentPermissions::INSERT_INTERNAL_COMMENTS ) ) : ?>
			<?php echo esc_html( __( 'Be the first to post one!', 'internal-comments' ) ); ?>
		<?php endif; ?>
	</div>

<?php else : ?>

	<ul class="dws-internal-comments__list">
		<?php foreach ( $internal_comments as $internal_comment ) : ?>
			<?php $classes = array_merge( array( 'dws-internal-comment' ), apply_filters( dws_ic_get_component_hook_tag( 'metabox-output', 'comment_classes' ), array(), $internal_comment ) ); ?>
			<li id="comment-<?php echo esc_attr( $internal_comment->comment_ID ); ?>" rel="<?php echo absint( $internal_comment->comment_ID ); ?>" class="<?php echo esc_attr( join( ' ', $classes ) ); ?>">
				<?php dws_ic_include_view( 'html-single-comment.php', array( 'internal_comment' => $internal_comment ) ); ?>
			</li>
			<?php
			dws_ic_include_view(
				'html-comment-replies-list.php',
				array(
					'internal_comment' => $internal_comment,
					'level'            => 1,
				)
			);
			?>
		<?php endforeach; ?>
	</ul>

<?php endif; ?>
