<?php
/**
 * Admin view: single comment output.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @package DeepWebSolutions\WP-Plugins\InternalComments
 *
 * @var     DWS_Internal_Comment    $internal_comment   The comment being outputted.
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="dws-internal-comment__content">
	<?php echo wpautop( wptexturize( wp_kses_post( $internal_comment->comment_content ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<div class="dws-internal-comment__other">
	<div class="dws-internal-comment__meta">
		<?php
		echo esc_html(
			wp_sprintf(
				/* translators: 1: Formatted date. 2: Formatted time. */
				__( 'Added on %1$s at %2$s by', 'internal-comments' ),
				get_comment_date( 'Y-m-d', $internal_comment->comment_ID ),
				get_comment_date( 'H:i:s', $internal_comment->comment_ID )
			)
		);
		?>

		<?php if ( $internal_comment->can_edit_author() ) : ?>
			<a href="<?php echo esc_url( get_edit_user_link( $internal_comment->user_id ) ); ?>" target="_blank">
				<?php echo esc_html( get_comment_author( $internal_comment->comment_ID ) ); ?>
			</a>
		<?php else : ?>
			<?php echo esc_html( get_comment_author( $internal_comment->comment_ID ) ); ?>
		<?php endif; ?>
	</div>
	<div class="dws-internal-comment__actions">
		<?php do_action( dws_ic_get_component_hook_tag( 'metabox-output', 'single_comment', array( 'actions', 'start' ) ), $internal_comment ); ?>
		<?php if ( $internal_comment->can_edit() ) : ?>
			<a href="<?php echo esc_url( $internal_comment->get_edit_comment_link() ); ?>" class="internal-comment-action edit-internal-comment" role="button" data-action="edit" data-nonce="<?php echo esc_attr( wp_create_nonce( "dws_edit_internal_comment_$internal_comment->comment_ID" ) ); ?>">
				<?php esc_html_e( 'Edit comment', 'internal-comments' ); ?>
			</a>
		<?php endif; ?>
		<?php if ( $internal_comment->can_trash() ) : ?>
			<a href="#" class="internal-comment-action trash-internal-comment" role="button" data-action="trash" data-nonce="<?php echo esc_attr( wp_create_nonce( "dws_trash_internal_comment_$internal_comment->comment_ID" ) ); ?>">
				<?php if ( empty( EMPTY_TRASH_DAYS ) ) : ?>
					<?php esc_html_e( 'Delete', 'internal-comments' ); ?>
				<?php else : ?>
					<?php esc_html_e( 'Move to Trash', 'internal-comments' ); ?>
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<?php do_action( dws_ic_get_component_hook_tag( 'metabox-output', 'single_comment', array( 'actions', 'end' ) ), $internal_comment ); ?>
	</div>
</div>
