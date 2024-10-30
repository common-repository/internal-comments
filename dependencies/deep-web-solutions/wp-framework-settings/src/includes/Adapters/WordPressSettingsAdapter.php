<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Adapters;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Callables;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsAdapterInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Interacts with the Settings API of WordPress itself.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Adapters
 */
class WordPressSettingsAdapter implements SettingsAdapterInterface {

	// region CREATE
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_menu_page( $page_title, $menu_title, string $menu_slug, string $capability, array $params = array() ) : string {
		return \add_menu_page( Strings::resolve( $page_title ), Strings::resolve( $menu_title ), $capability, $menu_slug, $params['function'] ?? '', $params['icon_url'] ?? '', $params['position'] ?? null );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function register_submenu_page( string $parent_slug, $page_title, $menu_title, string $menu_slug, string $capability, array $params = array() ) : ?string {
		$page_title = Strings::resolve( $page_title );
		$menu_title = Strings::resolve( $menu_title );
		$params     = \wp_parse_args(
			$params,
			array(
				'function' => '',
				'position' => null,
			)
		);
		switch ( $parent_slug ) {
			case 'plugins.php':
				$result = \add_plugins_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'themes.php':
				$result = \add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'options-general.php':
				$result = \add_options_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'tools.php':
				$result = \add_management_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'index.php':
				$result = \add_dashboard_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'edit.php':
				$result = \add_posts_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'upload.php':
				$result = \add_media_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'link-manager.php':
				$result = \add_links_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'edit.php?post_type=page':
				$result = \add_pages_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'edit-comments.php':
				$result = \add_comments_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			default:
				$result = \add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
		}
		return \false === $result ? null : $result;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_options_group( string $group_id, $group_title, $fields, string $page, array $params = array() ) : bool {
		\register_setting( $group_id, $group_id, array( 'type' => 'array' ) + ( $params['setting_args'] ?? array() ) );
		\add_settings_section( $group_id, Strings::resolve( $group_title ), $params['section_callback'] ?? '', $page );
		$fields = Arrays::validate( Callables::maybe_resolve( $fields ), array() );
		foreach ( $fields as $field ) {
			if ( isset( $field['id'], $field['title'], $field['callback'] ) ) {
				\add_settings_field( $field['id'], Strings::resolve( $field['title'] ), $field['callback'], $page, $group_id, $field['args'] ?? array() );
			}
		}
		return \true;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_generic_group( string $group_id, $group_title, $fields, array $locations, array $params = array() ) : bool {
		\add_meta_box( $group_id, Strings::resolve( $group_title ), $params['callback'] ?? '__return_empty_string', $locations, $params['context'] ?? 'advanced', $params['priority'] ?? 'default', array( 'fields' => Callables::maybe_resolve( $fields ) ) + ( $params['callback_args'] ?? array() ) );
		return \true;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_field( string $group_id, string $field_id, $field_title, string $field_type, array $params ) : bool {
		if ( isset( \get_registered_settings()[ $group_id ] ) ) {
			\add_settings_field( $field_id, Strings::resolve( $field_title ), $params['callback'] ?? '', $params['page'] ?? '', $group_id, array( 'type' => $field_type ) + ( $params['args'] ?? array() ) );
			return \true;
		}
		return \false;
	}
	// endregion
	// region READ
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_option_value( ?string $field_id, string $settings_id, array $params = array() ) {
		$params = \wp_parse_args( $params, array( 'default' => \false ) );
		if ( \is_multisite() ) {
			$params   = $this->parse_network_params( $params );
			$settings = \false === $params['network_id'] ? \get_blog_option( $params['blog_id'], $settings_id, $params['default'] ) : \get_network_option( $params['network_id'], $settings_id, $params['default'] );
		} else {
			$settings = \get_option( $settings_id, $params['default'] );
		}
		return empty( $field_id ) ? $settings : $settings[ $field_id ] ?? $params['default'];
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_field_value( string $field_id, $object_id, array $params = array() ) {
		$params = \wp_parse_args(
			$params,
			array(
				'meta_type' => 'post',
				'single'    => \false,
				'raw'       => \false,
			)
		);
		return $params['raw'] ? \get_metadata_raw( $params['meta_type'], $object_id, $field_id, $params['single'] ) : \get_metadata( $params['meta_type'], $object_id, $field_id, $params['single'] );
	}
	// endregion
	// region UPDATE
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function update_option_value( ?string $field_id, $value, string $settings_id, array $params = array() ) : bool {
		$params = \wp_parse_args( $params, array( 'default' => \false ) );
		if ( ! empty( $field_id ) ) {
			$options              = $this->get_option_value( null, $settings_id, $params );
			$options[ $field_id ] = $value;
			$value                = $options;
		}
		if ( \is_multisite() ) {
			$params = $this->parse_network_params( $params );
			return \false === $params['network_id'] ? \update_blog_option( $params['blog_id'], $settings_id, $value ) : \update_network_option( $params['network_id'], $settings_id, $value );
		} else {
			return \update_option( $settings_id, $value, $params['autoload'] ?? null );
		}
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params = array() ) {
		return \update_metadata( $params['meta_type'] ?? 'post', $object_id, $field_id, $value, $params['prev_value'] ?? '' );
	}
	// endregion
	// region DELETE
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_option_value( ?string $field_id, string $settings_id, array $params = array() ) : bool {
		if ( ! empty( $field_id ) ) {
			$options = $this->get_option_value( null, $settings_id, $params );
			unset( $options[ $field_id ] );
			return $this->update_option_value( null, $options, $settings_id, $params );
		} elseif ( \is_multisite() ) {
			$params = $this->parse_network_params( $params );
			return \false === $params['network_id'] ? \delete_blog_option( $params['blog_id'], $settings_id ) : \delete_network_option( $params['network_id'], $settings_id );
		} else {
			return \delete_option( $settings_id );
		}
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_field_value( string $field_id, $object_id, array $params = array() ) : bool {
		return \delete_metadata( $params['meta_type'] ?? 'post', $object_id, $field_id, $params['meta_value'] ?? '', $params['delete_all'] ?? \false );
	}
	// endregion
	// region HELPERS
	/**
	 * Ensures parameters needed for working with multisite read/update/delete operations are present.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $params     Parameters to parse.
	 *
	 * @return  array
	 */
	protected function parse_network_params( array $params ) : array {
		return \wp_parse_args(
			$params,
			array(
				'network_id' => \false,
				// set to NULL or INT to use network-level options
				'blog_id'    => null,
			)
		);
	}
	// endregion
}
