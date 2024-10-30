<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Functionalities;

use DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Actions\InitializeSettingsServiceTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsServiceRegisterInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Actions\SetupHooksTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for standardizing the registration of an options group.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Functionalities
 */
abstract class AbstractOptionsGroupFunctionality extends AbstractPluginFunctionality implements SettingsServiceRegisterInterface {

	// region TRAITS
	use InitializeSettingsServiceTrait {
		get_option_value as protected get_option_value_trait;
		get_field_value as private;
		update_option_value as protected update_option_value_trait;
		update_field_value as private;
	}
	use SetupHooksTrait;
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_parent() : ?AbstractOptionsPageFunctionality {
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->parent;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	abstract public function get_option_value( string $field_id);
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	abstract public function update_option_value( string $field_id, $value);
	/**
	 * Deletes an option's value using the handler of choice.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id   The ID of the options field to delete.
	 *
	 * @return  mixed
	 */
	abstract public function delete_option_value( string $field_id);
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_hooks( HooksService $hooks_service ) : void {
		$hooks_service->add_filter( $this->get_parent()->get_hook_tag( 'get_option_value' ), $this, 'maybe_get_option_value', 10, 2, 'direct' );
		$hooks_service->add_filter( $this->get_parent()->get_hook_tag( 'update_option_value' ), $this, 'maybe_update_option_value', 10, 3, 'direct' );
		$hooks_service->add_filter( $this->get_parent()->get_hook_tag( 'delete_option_value' ), $this, 'maybe_delete_option_value', 10, 2, 'direct' );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_settings( SettingsService $settings_service ) : void {
		$this->register_options_group( $settings_service, $this->get_parent() );
	}
	// endregion
	// region METHODS
	/**
	 * Returns the ID of the options group. Needed for registering the group itself and for performing CRUD operations
	 * on the options later on.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_group_id() : string {
		$options_prefix = $this->get_parent()->get_options_name_prefix();
		$options_group  = $this->get_group_name();
		return Strings::maybe_suffix( $options_prefix, '_' ) . $options_group;
	}
	/**
	 * Returns the name of the group for purposes of retrieving options.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_group_name() : string {
		return Strings::replace_placeholders(
			self::get_safe_name(),
			array(
				'_settings' => '',
				'_options'  => '',
				'_'         => '-',
			)
		);
	}
	/**
	 * Returns the options group's public title.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	abstract public function get_group_title() : string;
	/**
	 * Returns the options fields' definition.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array[]
	 */
	public function get_group_fields() : array {
		return \apply_filters( $this->get_hook_tag( 'get_group_fields' ), $this->get_group_fields_helper() );
	}
	// endregion
	// region HOOKS
	/**
	 * Retrieves an option's value that was queried via the page component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   null|mixed  $value      The value so far.
	 * @param   string      $field_id   The prefixed field ID.
	 *
	 * @return  mixed
	 */
	public function maybe_get_option_value( $value, string $field_id ) {
		$return = $value;
		if ( \is_null( $return ) ) {
			$field_prefix = Strings::maybe_suffix( $this->get_group_name(), '/' );
			if ( Strings::starts_with( $field_id, $field_prefix ) ) {
				$return = $this->get_option_value( Strings::maybe_unprefix( $field_id, $field_prefix ) );
			}
		}
		return $return;
	}
	/**
	 * Updates a field's value that was updated via the page component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   bool    $updated    Whether the value was updated already or not.
	 * @param   string  $field_id   The prefixed field ID.
	 * @param   mixed   $value      Value to update to.
	 *
	 * @return bool
	 */
	public function maybe_update_option_value( bool $updated, string $field_id, $value ) : bool {
		$return = $updated;
		if ( \false === $updated ) {
			$field_prefix = Strings::maybe_suffix( $this->get_group_name(), '/' );
			if ( Strings::starts_with( $field_id, $field_prefix ) ) {
				$this->update_option_value( Strings::maybe_unprefix( $field_id, $field_prefix ), $value );
				$return = \true;
			}
		}
		return $return;
	}
	/**
	 * Deletes a field's value that was deleted via the page component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   bool    $deleted    Whether the value was deleted already or not.
	 * @param   string  $field_id   The prefixed field ID.
	 *
	 * @return  bool
	 */
	public function maybe_delete_option_value( bool $deleted, string $field_id ) : bool {
		$return = $deleted;
		if ( \false === $deleted ) {
			$field_prefix = Strings::maybe_suffix( $this->get_group_name(), '/' );
			if ( Strings::starts_with( $field_id, $field_prefix ) ) {
				$this->delete_option_value( Strings::maybe_unprefix( $field_id, $field_prefix ) );
				$return = \true;
			}
		}
		return $return;
	}
	// endregion
	// region HELPERS
	/**
	 * Registers the options group with the handler of choice.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsService                     $settings_service   Instance of the settings service.
	 * @param   AbstractOptionsPageFunctionality    $options_page       Instance of the options page the group belongs to.
	 */
	abstract protected function register_options_group( SettingsService $settings_service, AbstractOptionsPageFunctionality $options_page);
	/**
	 * Child classes should return their fields definitions here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array[]
	 */
	abstract protected function get_group_fields_helper() : array;
	// endregion
}
