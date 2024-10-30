<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance that can interact with a settings framework.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
interface SettingsAdapterInterface {

	// region CREATE
	/**
	 * Registers a new WordPress admin page.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string|callable     $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string|callable     $menu_title     The text to be used for the menu.
	 * @param   string              $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                              include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                              with sanitize_key().
	 * @param   string              $capability     The capability required for this menu to be displayed to the user.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function register_menu_page( $page_title, $menu_title, string $menu_slug, string $capability, array $params);
	/**
	 * Registers a new WordPress child admin page.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $parent_slug    The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 * @param   string|callable     $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string|callable     $menu_title     The text to be used for the menu.
	 * @param   string              $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                              include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                              with sanitize_key().
	 * @param   string              $capability     The capability required for this menu to be displayed to the user.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function register_submenu_page( string $parent_slug, $page_title, $menu_title, string $menu_slug, string $capability, array $params);
	/**
	 * Registers a group of settings to be outputted on an admin-side settings page.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $group_id       The ID of the settings group.
	 * @param   string|callable     $group_title    The title of the settings group.
	 * @param   array|callable      $fields         The fields to be registered with the group.
	 * @param   string              $page           The settings page on which the group's fields should be displayed.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function register_options_group( string $group_id, $group_title, $fields, string $page, array $params);
	/**
	 * Registers a group of settings.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $group_id       The ID of the settings group.
	 * @param   string|callable     $group_title    The title of the settings group.
	 * @param   array|callable      $fields         The fields to be registered with the group.
	 * @param   array               $locations      Where the group should be outputted.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function register_generic_group( string $group_id, $group_title, $fields, array $locations, array $params);
	/**
	 * Registers a custom field dynamically at a later point than the parent group's creation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $group_id       The ID of the parent group that the dynamically added field belongs to.
	 * @param   string              $field_id       The ID of the newly registered field.
	 * @param   string|callable     $field_title    The title of the newly registered field.
	 * @param   string              $field_type     The type of custom field being registered.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function register_field( string $group_id, string $field_id, $field_title, string $field_type, array $params);
	// endregion
	// region READ
	/**
	 * Reads a setting's value from the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field within the settings to read from the database.
	 * @param   string  $settings_id    The ID of the settings group to read from the database.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function get_option_value( string $field_id, string $settings_id, array $params);
	/**
	 * Reads a field's value from the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field to read from the database.
	 * @param   mixed   $object_id      The ID of the object the data is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function get_field_value( string $field_id, $object_id, array $params);
	// endregion
	// region UPDATE
	/**
	 * Updates a setting's value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field within the settings to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   string  $settings_id    The ID of the settings group to update.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function update_option_value( string $field_id, $value, string $settings_id, array $params);
	/**
	 * Updates a field's value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   mixed   $object_id      The ID of the object the update is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params);
	// endregion
	// region DELETE
	/**
	 * Deletes a setting from the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the settings field to remove from the database. Empty string to delete the whole group.
	 * @param   string  $settings_id    The ID of the settings group to delete the field from.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function delete_option_value( string $field_id, string $settings_id, array $params);
	/**
	 * Deletes a field's value from the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id   The ID of the field to delete from the database.
	 * @param   mixed   $object_id  The ID of the object the deletion is for.
	 * @param   array   $params     Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function delete_field_value( string $field_id, $object_id, array $params);
	// endregion
}
