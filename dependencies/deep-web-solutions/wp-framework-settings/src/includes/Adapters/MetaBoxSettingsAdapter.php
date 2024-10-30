<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Adapters;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotSupportedException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Booleans;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Callables;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsAdapterInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Interacts with the API of the Meta Box plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Adapters
 *
 * @see     https://metabox.io/
 */
class MetaBoxSettingsAdapter implements SettingsAdapterInterface {

	// region CREATE
	/**
	 * {@inheritDoc}
	 *
	 * @see     https://docs.metabox.io/extensions/mb-settings-page/
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_menu_page( $page_title, $menu_title, string $menu_slug, string $capability, array $params ) : bool {
		return \add_filter(
			'mb_settings_pages',
			function ( $settings_pages ) use ( $page_title, $menu_title, $menu_slug, $capability, $params ) {
				$settings_pages[] = array(
					'id'         => $menu_slug,
					'menu_title' => Strings::resolve( $menu_title ),
					'page_title' => Strings::resolve( $page_title ),
					'capability' => $capability,
				) + $params;
				return $settings_pages;
			}
		);
	}
	/**
	 * {@inheritDoc}
	 *
	 * @see     https://docs.metabox.io/extensions/mb-settings-page/
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_submenu_page( string $parent_slug, $page_title, $menu_title, string $menu_slug, string $capability, array $params ) : bool {
		return $this->register_menu_page( $page_title, $menu_title, $menu_slug, $capability, array( 'parent' => $parent_slug ) + $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @see     https://docs.metabox.io/extensions/mb-settings-page/
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_options_group( string $group_id, $group_title, $fields, string $page, array $params ) : bool {
		return $this->register_generic_group( $group_id, $group_title, $fields, array( 'settings_pages' => $page ), $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @see     https://docs.metabox.io/creating-meta-boxes/#using-code
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_generic_group( string $group_id, $group_title, $fields, array $locations, array $params ) : bool {
		return \add_filter(
			'rwmb_meta_boxes',
			function ( $meta_boxes ) use ( $group_id, $group_title, $fields, $locations, $params ) {
				$meta_boxes[] = array(
					'id'     => $group_id,
					'title'  => Strings::resolve( $group_title ),
					'fields' => Arrays::validate( Callables::maybe_resolve( $fields ), array() ),
				) + $locations + $params;
				return $meta_boxes;
			}
		);
	}
	/**
	 * {@inheritDoc}
	 *
	 * @see     https://docs.metabox.io/field-settings/
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_field( string $group_id, string $field_id, $field_title, string $field_type, array $params = array() ) : bool {
		return \add_filter(
			'rwmb_meta_boxes',
			function ( $meta_boxes ) use ( $group_id, $field_id, $field_title, $field_type, $params ) {
				foreach ( $meta_boxes as $k => $meta_box ) {
					if ( isset( $meta_box['id'] ) && $group_id === $meta_box['id'] ) {
						$meta_boxes[ $k ]['fields'][] = array(
							'id'   => $field_id,
							'name' => Strings::resolve( $field_title ),
							'type' => $field_type,
						) + $params;
						break;
					}
				}
				return $meta_boxes;
			},
			20
		);
	}
	// endregion
	// region READ
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_option_value( string $field_id, string $settings_id, array $params ) {
		$params['object_type'] = \is_multisite() && Booleans::maybe_cast( $params['network'] ?? \false, \false ) ? 'network_setting' : 'setting';
		return $this->get_field_value( $field_id, $settings_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_field_value( string $field_id, $object_id = null, array $params = array() ) {
		return \rwmb_meta( $field_id, $params, $object_id );
	}
	// endregion
	// region UPDATE
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function update_option_value( string $field_id, $value, string $settings_id, array $params ) : bool {
		$params['object_type'] = \is_multisite() && Booleans::maybe_cast( $params['network'] ?? \false, \false ) ? 'network_setting' : 'setting';
		return $this->update_field_value( $field_id, $value, $settings_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params ) : bool {
		\rwmb_set_meta( $object_id, $field_id, $value, $params );
		return \true;
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
	 *
	 * @throws  NotSupportedException       The Meta Box plugin does NOT implement any functions for deleting values. Use the WP adapter.
	 */
	public function delete_option_value( string $field_id, string $settings_id, array $params ) {
		throw new NotSupportedException();
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @throws  NotSupportedException       The Meta Box plugin does NOT implement any functions for deleting values. Use the WP adapter.
	 */
	public function delete_field_value( string $field_id, $object_id, array $params ) {
		throw new NotSupportedException();
	}
	// endregion
}
