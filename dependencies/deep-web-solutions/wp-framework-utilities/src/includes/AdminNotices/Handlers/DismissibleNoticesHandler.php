<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StoreInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Assets;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\HooksHelpersAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\AdminNoticeInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Notices\DismissibleAdminNotice;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterTrait;
use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Handles dismissible notices.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices\Handlers
 */
class DismissibleNoticesHandler extends SimpleNoticesHandler implements PluginAwareInterface, HooksHelpersAwareInterface, HooksServiceRegisterInterface {

	// region TRAITS
	use PluginAwareTrait;
	use HooksServiceRegisterTrait;
	// endregion
	// region INHERITED METHODS
	/**
	 * Returns the ID of the handler as the dismissible notice model class. Since there should be only one handler per type
	 * of admin notices, this is safe.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_id() : string {
		return DismissibleAdminNotice::class;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_hooks( HooksService $hooks_service ) : void {
		$hooks_service->add_action( 'admin_footer', $this, 'output_dismiss_js' );
		$hooks_service->add_action( 'wp_ajax_' . $this->get_hook_tag( 'dismiss_notice' ), $this, 'handle_ajax_dismiss' );
	}
	// endregion
	// region HOOKS
	/**
	 * Output the JS that handles the notice dismiss action.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function output_dismiss_js() : void {
		if ( \false === $this->has_output ) {
			return;
		}
		\ob_start();
		?>

		( function( $ ) {
			$( '.dws-framework-notice-%plugin_slug%' ).on( 'click.wp-dismiss-notice', '.notice-dismiss', function( e ) {
				var notice = $( this ).closest( '.dws-framework-notice' );
				$.ajax( {
					url: ajaxurl,
					method: 'POST',
					data: {
						action: '%action%',
						handle: $( notice ).data( 'handle' ),
						store: $( notice ).data( 'store' ),
						_wpnonce: '%nonce%'
					}
				} );
			} );
		} ) ( jQuery );

		<?php
		$js_script = Strings::replace_placeholders(
			\ob_get_clean(),
			array(
				'%plugin_slug%' => \esc_js( $this->get_plugin()->get_plugin_slug() ),
				'%action%'      => \esc_js( $this->get_hook_tag( 'dismiss_notice' ) ),
				'%nonce%'       => \esc_js( \wp_create_nonce( $this->get_plugin()->get_plugin_safe_slug() . '_dismiss_notice' ) ),
			)
		);
		if ( \function_exists( '\wp_print_inline_script_tag' ) ) {
			\wp_print_inline_script_tag( $js_script );
		} else {
			echo "<script type='text/javascript'>{$js_script}</script>";
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
	/**
	 * Intercepts an AJAX request for dismissing a given notice.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  ContainerExceptionInterface     Thrown when an error occurs while dismissing the notice.
	 */
	public function handle_ajax_dismiss() : void {
		if ( \is_user_logged_in() && \check_ajax_referer( $this->get_plugin()->get_plugin_safe_slug() . '_dismiss_notice', \false, \false ) ) {
			$handle = \sanitize_key( $_POST['handle'] ?? '' );
			$store  = \sanitize_key( $_POST['store'] ?? '' );
			$this->dismiss_notice( $handle, $store );
		}
		\wp_die();
	}
	// endregion
	// region METHODS
	/**
	 * Marks a notice as dismissed.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $handle         The ID of the notice.
	 * @param   string      $store_id       The ID of the store the notice is stored in.
	 *
	 * @throws  ContainerExceptionInterface     Error while retrieving the entries.
	 *
	 * @return  bool|null
	 */
	public function dismiss_notice( string $handle, string $store_id ) : ?bool {
		$store  = $this->get_store_entry( $store_id );
		$notice = $this->get_notice( $store_id, $handle );
		if ( ! $store instanceof StoreInterface || ! \is_a( $notice, DismissibleAdminNotice::class ) ) {
			return null;
		}
		$notice->set_dismissed( \true );
		$result = $store->update( $notice );
		return \is_null( $result ) || $result;
	}
	/**
	 * Returns whether a given notice is dismissed or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $handle         The ID of the notice.
	 * @param   string      $store_id       The ID of the store the notice is stored in.
	 *
	 * @throws  ContainerExceptionInterface     Error while retrieving the entries.
	 *
	 * @return  bool|null
	 */
	public function is_dismissed_notice( string $handle, string $store_id ) : ?bool {
		$notice = $this->get_notice( $store_id, $handle );
		return \is_a( $notice, DismissibleAdminNotice::class ) ? $notice->is_dismissed() : null;
	}
	/**
	 * Returns all dismissed notices in a given store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $store_id   The ID of the store to retrieve the dismissed notices from.
	 *
	 * @throws  ContainerExceptionInterface     Error while retrieving the entries.
	 *
	 * @return  DismissibleAdminNotice[]
	 */
	public function get_dismissed_notices( string $store_id ) : array {
		return \array_filter( $this->get_notices( $store_id ), fn( DismissibleAdminNotice $notice) => $notice->is_dismissed() );
	}
	// endregion
	// region INHERITED HELPERS
	/**
	 * Manipulate the output of the notice to contain the HTML attributes needed for the AJAX dismiss call.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   AdminNoticeInterface    $notice     Notice to output.
	 * @param   StoreInterface          $store      Store holding the notice.
	 *
	 * @return  OutputFailureException|null
	 */
	protected function output_notice( AdminNoticeInterface $notice, StoreInterface $store ) : ?OutputFailureException {
		\ob_start();
		$result = parent::output_notice( $notice, $store );
		if ( ! \is_null( $result ) ) {
			\ob_end_clean();
			return $result;
		}
		$notice_html = \ob_get_clean();
		$notice_html = Strings::replace_placeholders(
			$notice_html,
			array(
				'dws-framework-notice' => 'dws-framework-notice dws-framework-notice-' . \esc_attr( $this->get_plugin()->get_plugin_slug() ),
				'class='               => 'data-store="' . \esc_attr( $store->get_id() ) . '" class=',
			)
		);
		echo $notice_html;
        // phpcs:ignore
        return null;
	}
	// endregion
}
