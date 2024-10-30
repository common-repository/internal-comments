<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Adapters;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Booleans;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Callables;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsAdapterInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Interacts with the API of the ACF plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Adapters
 *
 * @see     https://www.advancedcustomfields.com/
 */
class ACFSettingsAdapter implements SettingsAdapterInterface {

	// region CREATE
	/**
	 * {@inheritDoc}
	 *
	 * @see     https://www.advancedcustomfields.com/resources/acf_add_options_page/
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_menu_page( $page_title, $menu_title, string $menu_slug, string $capability, array $params = array() ) : ?array {
		$result = null;
		if ( \function_exists( '\acf_add_options_page' ) ) {
			// ACF Pro required.
			$result = \acf_add_options_page(
				array(
					'page_title' => Strings::resolve( $page_title ),
					'menu_title' => Strings::resolve( $menu_title ),
					'menu_slug'  => $menu_slug,
					'capability' => $capability,
				) + $params
			) ?: null;
            // phpcs:ignore
        }
		return $result;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @see     https://www.advancedcustomfields.com/resources/acf_add_options_sub_page/
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_submenu_page( string $parent_slug, $page_title, $menu_title, string $menu_slug, string $capability, array $params = array() ) : ?array {
		$result = null;
		if ( \function_exists( '\acf_add_options_sub_page' ) ) {
			// ACF Pro required.
			$result = \acf_add_options_sub_page(
				array( 'parent_slug' => $parent_slug ) + array(
					'page_title' => Strings::resolve( $page_title ),
					'menu_title' => Strings::resolve( $menu_title ),
					'menu_slug'  => $menu_slug,
					'capability' => $capability,
				) + $params
			) ?: null;
		}
		return $result;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_options_group( string $group_id, $group_title, $fields, string $page, array $params = array() ) : bool {
		return $this->register_generic_group(
			$group_id,
			$group_title,
			$fields,
			array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => $page,
					),
				),
			),
			$params
		);
	}
	/**
	 * {@inheritDoc}
	 *
	 * @see     https://www.advancedcustomfields.com/resources/register-fields-via-php/
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_generic_group( string $group_id, $group_title, $fields, array $locations, array $params = array() ) : bool {
		$group_id = Strings::maybe_prefix( $group_id, 'group_' );
		$params   = \wp_parse_args( $params, array( 'location' => array() ) );
		return \acf_add_local_field_group(
			array(
				'key'      => $group_id,
				'title'    => Strings::resolve( $group_title ),
				'fields'   => Arrays::validate( Callables::maybe_resolve( $fields ), array() ),
				'location' => $locations,
			) + $params
		);
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_field( string $group_id, string $field_id, $field_title, string $field_type, array $params = array() ) : bool {
		$group_id = Strings::starts_with( $group_id, 'group_' ) || Strings::starts_with( $group_id, 'field_' ) ? $group_id : "group_{$group_id}";
		\acf_add_local_field(
			array(
				'key'    => Strings::maybe_prefix( $field_id, 'field_' ),
				'label'  => Strings::resolve( $field_title ),
				'name'   => $params['name'] ?? '',
				'type'   => $field_type,
				'parent' => $group_id,
			) + $params
		);
		return \true;
	}
	// endregion
	// region READ
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 * @noinspection PhpParameterNameChangedDuringInheritanceInspection
	 */
	public function get_option_value( string $field_id, string $unused = null, array $params = array() ) {
		return $this->get_field_value( $field_id, 'options', $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_field_value( string $field_id, $object_id = \false, array $params = array() ) {
		return \get_field( $field_id, $object_id, Booleans::maybe_cast( $params['format_value'] ?? \true, \true ) );
	}
	// endregion
	// region UPDATE
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 * @noinspection PhpParameterNameChangedDuringInheritanceInspection
	 */
	public function update_option_value( string $field_id, $value, string $unused = null, array $params = array() ) : bool {
		return $this->update_field_value( $field_id, 'options', $value, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function update_field_value( string $field_id, $value, $object_id = \false, array $params = array() ) : bool {
		return \update_field( $field_id, $value, $object_id );
	}
	// endregion
	// region DELETE
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 * @noinspection PhpParameterNameChangedDuringInheritanceInspection
	 */
	public function delete_option_value( string $field_id, string $unused = null, array $params = array() ) : bool {
		return $this->delete_field_value( $field_id, array( 'post_id' => 'options' ) + $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_field_value( string $field_id, $object_id = \false, array $params = array() ) : bool {
		return Booleans::maybe_cast( $params['sub_field'] ?? \false, \false ) ? \delete_sub_field( $field_id, $object_id ) : \delete_field( $field_id, $object_id );
	}
	// endregion
}
