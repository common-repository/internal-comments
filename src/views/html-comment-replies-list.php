<?php
/**
 * Admin view: comment replies list output.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @package DeepWebSolutions\WP-Plugins\InternalComments
 *
 * @var     DWS_Internal_Comment    $internal_comment   The comment being outputted.
 * @var     int                     $level              The depth of the reply.
 */

defined( 'ABSPATH' ) || exit;

if ( $internal_comment->get_children() ) : ?>

<li id="replies-comment-<?php echo esc_attr( $internal_comment->comment_ID ); ?>" class="dws-internal-comment-replies">
	<ul class="dws-internal-comments-replies__list" data-level="<?php echo esc_attr( $level ); ?>">
		<?php
		foreach ( $internal_comment->get_children() as $reply_comment ) :
			$reply_comment = new DWS_Internal_Comment( $reply_comment );
			$classes       = array_merge( array( 'dws-internal-comment', 'internal-comment-reply' ), apply_filters( dws_ic_get_component_hook_tag( 'metabox-output', 'comment_classes' ), array(), $reply_comment ) );
			?>
			<li id="reply-<?php echo \esc_attr( $reply_comment->comment_ID ); ?>" rel="<?php echo \absint( $reply_comment->comment_ID ); ?>" class="<?php echo \esc_attr( \join( ' ', $classes ) ); ?>">
				<?php dws_ic_include_view( 'html-single-comment.php', array( 'internal_comment' => $reply_comment ) ); ?>
			</li>
			<?php
			dws_ic_include_view(
				'html-comment-replies-list.php',
				array(
					'internal_comment' => $reply_comment,
					'level'            => $level + 1,
				)
			);
			?>
		<?php endforeach; ?>
	</ul>
</li>

	<?php
endif;
