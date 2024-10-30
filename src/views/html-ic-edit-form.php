<?php
/**
 * Admin view: edit comment form.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @package DeepWebSolutions\WP-Plugins\InternalComments
 */

defined( 'ABSPATH' ) || exit; ?>

<script type="text/html" id="tmpl-dws-edit-internal-comment">
	<div id="edit-comment-{{data.comment_ID}}" class="dws-edit-internal-comment dws-internal-comments-inline-action">
		<div class="dws-edit-internal-comment__textarea dws-internal-comments-inline-action__textarea">
			<label for="edit-internal-comment">
				<?php esc_html_e( 'Edit internal comment', 'internal-comments' ); ?>
			</label>
			<textarea id="edit-internal-comment" placeholder="<?php esc_attr_e( 'Edit your comment here &hellip;', 'internal-comments' ); ?>" rows="4">{{data.comment_content}}</textarea>
		</div>
		<div class="dws-edit-internal-comment__actions dws-internal-comments-inline-action__actions">
			<?php do_action( dws_ic_get_component_hook_tag( 'metabox-output', 'inline_actions' ) ); ?>
			<?php do_action( dws_ic_get_component_hook_tag( 'metabox-output', 'inline_actions', 'edit' ) ); ?>
			<button type="reset" class="cancel-edit-internal-comment button">
				<?php esc_html_e( 'Cancel', 'internal-comments' ); ?>
			</button>
			<button type="button" class="save-edit-internal-comment button" data-nonce="{{data.nonce}}" data-comment-id="{{data.comment_ID}}">
				<?php esc_html_e( 'Save comment', 'internal-comments' ); ?>
			</button>
		</div>
	</div>
</script>
