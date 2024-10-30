<?php

namespace DeepWebSolutions\Plugins\InternalComments;

use DeepWebSolutions\Plugins\InternalComments\Permissions\SettingsPermissions;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\OutputtableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Functionalities\AbstractValidatedOptionsPageFunctionality;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsService;

\defined( 'ABSPATH' ) || exit;

/**
 * Registers and outputs the plugin's settings page.
 *
 * @since   1.0.0
 * @version 1.1.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class Settings extends AbstractValidatedOptionsPageFunctionality implements OutputtableInterface {
	// region TRAITS

	use OutputLocalTrait;

	// endregion

	// region INHERITED METHODS

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	protected function get_di_container_children(): array {
		return array( Settings\GeneralSettings::class, Settings\PluginSettings::class );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	public function get_options_name_prefix(): string {
		return 'dws-internal-comments';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	public function get_page_slug(): string {
		return 'dws-internal-comments';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	public function get_page_title(): string {
		return \__( 'Internal Comments Settings', 'internal-comments' );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	protected function register_options_page( SettingsService $settings_service ) {
		$settings_service->register_submenu_page(
			'options-general.php',
			array( $this, 'get_page_title' ),
			function() {
				return \__( 'Internal Comments', 'internal-comments' );
			},
			$this->get_page_slug(),
			SettingsPermissions::MANAGE_OPTIONS,
			array( 'function' => array( $this, 'output' ) )
		);
	}

	// endregion

	// region OUTPUT

	/**
	 * Outputs the settings page fields.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  null
	 */
	protected function output_local(): ?OutputFailureException {
		$current_tab   = Strings::maybe_cast_input( INPUT_GET, 'tab', 'general' );
		$current_group = null;
		?>

		<div class="wrap">
			<h1>
				<?php echo \esc_html( \get_admin_page_title() ); ?>
			</h1>

			<h2 class="nav-tab-wrapper">
				<?php foreach ( $this->get_children() as $child ) : ?>
					<?php
					$class = '';
					if ( $child->get_group_name() === $current_tab ) {
						$class         = 'nav-tab-active';
						$current_group = $child;
					}
					?>
					<a class="nav-tab <?php echo \esc_attr( $class ); ?>" href="?page=<?php echo \esc_attr( $this->get_page_slug() ); ?>&tab=<?php echo \esc_attr( $child->get_group_name() ); ?>">
						<?php echo \esc_html( $child->get_group_title() ); ?>
					</a>
				<?php endforeach; ?>
			</h2>

			<!--suppress HtmlUnknownTarget -->
			<form method="post" action="options.php">
				<?php \settings_fields( $current_group->get_group_id() ); ?>
				<?php \do_settings_sections( $current_group->get_group_id() ); ?>
				<?php \submit_button(); ?>
			</form>
		</div>

		<?php

		return null;
	}

	// endregion
}
