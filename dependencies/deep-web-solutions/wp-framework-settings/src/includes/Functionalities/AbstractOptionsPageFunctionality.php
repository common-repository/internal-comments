<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Functionalities;

use DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality;
use DWS_IC_Deps\DeepWebSolutions\Framework\Core\Actions\Installable\UninstallFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Core\Actions\UninstallableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Actions\InitializeSettingsServiceTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Actions\SetupSettingsTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsServiceAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsServiceRegisterInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for standardizing the registration of an options page with one or more option groups.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Functionalities
 */
abstract class AbstractOptionsPageFunctionality extends AbstractPluginFunctionality implements UninstallableInterface, SettingsServiceAwareInterface {

	// region TRAITS
	use InitializeSettingsServiceTrait {
		get_option_value as protected get_option_value_trait;
		get_field_value as private;
		update_option_value as protected update_option_value_trait;
		update_field_value as private;
	}
	use SetupSettingsTrait;
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_settings( SettingsService $settings_service ) : void {
		$this->register_options_page( $settings_service );
		\do_action( $this->get_hook_tag( 'registered_options_page' ), $settings_service );
		foreach ( $this->get_children() as $child ) {
			if ( $child instanceof SettingsServiceRegisterInterface ) {
				$child->register_settings( $settings_service );
			}
		}
		\do_action( $this->get_hook_tag( 'registered_options_groups' ), $settings_service );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_option_value( string $field_id ) {
		return \apply_filters( $this->get_hook_tag( 'get_option_value' ), null, $field_id );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function update_option_value( string $field_id, $value ) : bool {
		return \apply_filters( $this->get_hook_tag( 'update_option_value' ), \false, $field_id, $value );
	}
	/**
	 * Attempts to delete the raw value of a given field.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id   The ID of the field to delete prefixed by the group_name and a forward slash.
	 *
	 * @return  bool
	 */
	public function delete_option_value( string $field_id ) : bool {
		return \apply_filters( $this->get_hook_tag( 'delete_option_value' ), \false, $field_id );
	}
	// endregion
	// region METHODS
	/**
	 * Returns the prefix of all the options registered by this functionality.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	abstract public function get_options_name_prefix() : string;
	/**
	 * Returns the options page's slug.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	abstract public function get_page_slug() : string;
	/**
	 * Returns the options page's title.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	abstract public function get_page_title() : string;
	// endregion
	// region INSTALLATION
	/**
	 * Removes all the options registered by this functionality from the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  UninstallFailureException|null
	 */
	public function uninstall() : ?UninstallFailureException {
		global $wpdb;
		/* @noinspection SqlNoDataSourceInspection */
		$result = $wpdb->query(
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $this->get_options_name_prefix() . '%' )
		);
		if ( \false === $result ) {
			return new UninstallFailureException( \__( 'Failed to delete the plugin options from the database', 'internal-comments' ) );
		}
		return null;
	}
	// endregion
	// region HELPERS
	/**
	 * Registers the options page with the handler of choice.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsService     $settings_service   Instance of the settings service.
	 */
	abstract protected function register_options_page( SettingsService $settings_service);
	// endregion
}
