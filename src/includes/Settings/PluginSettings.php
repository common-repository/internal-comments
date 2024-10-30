<?php

namespace DeepWebSolutions\Plugins\InternalComments\Settings;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Booleans;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Functionalities\AbstractOptionsPageFunctionality;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Functionalities\AbstractValidatedOptionsGroupFunctionality;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation\ValidationTypesEnum;

\defined( 'ABSPATH' ) || exit;

/**
 * Registers and outputs the plugin-level settings.
 *
 * @since   1.1.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class PluginSettings extends AbstractValidatedOptionsGroupFunctionality {
	// region INHERITED METHODS

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	public function get_option_value( string $field_id ) {
		return $this->get_option_value_trait( $field_id, $this->get_group_id(), array( 'default' => null ) );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	public function update_option_value( string $field_id, $value ) {
		return $this->update_option_value_trait( $field_id, $value, $this->get_group_id() );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	public function delete_option_value( string $field_id ) {
		return $this->get_settings_service()->delete_option_value( $field_id, $this->get_group_id() );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	public function get_group_title(): string {
		return \_x( 'Plugin', 'settings heading', 'internal-comments' );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	protected function register_options_group( SettingsService $settings_service, AbstractOptionsPageFunctionality $options_page ) {
		$settings_service->register_options_group(
			$this->get_group_id(),
			'',
			array( $this, 'get_group_fields' ),
			$this->get_group_id()
		);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.2.0
	 */
	public function get_group_fields_helper(): array {
		return array(
			array(
				'id'       => 'remove-data-uninstall',
				'title'    => function() {
					return \__( 'Remove all data on uninstallation?', 'internal-comments' );
				},
				'callback' => function() {
					dws_ic_include_view(
						'settings/html-settings-checkbox.php',
						array(
							'id'      => "{$this->get_group_id()}_remove-data-uninstall",
							'name'    => "{$this->get_group_id()}[remove-data-uninstall]",
							'value'   => Booleans::to_string( true ),
							'checked' => \checked( true, $this->get_validated_option_value( 'remove-data-uninstall' ), false ),
							'label'   => \__( 'Remove all plugin settings and all posted internal comments permanently when removing the plugin. This action is irreversible.', 'internal-comments' ),
						)
					);
				},
			),
		);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	protected function validate_option_value_helper( $value, string $field_id ) {
		switch ( $field_id ) {
			case 'remove-data-uninstall':
				$value = $this->validate_value( $value, $field_id, ValidationTypesEnum::BOOLEAN );
				break;
		}

		return $value;
	}

	// endregion
}
