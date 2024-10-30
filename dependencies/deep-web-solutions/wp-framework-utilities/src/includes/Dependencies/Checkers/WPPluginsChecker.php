<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Checkers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Booleans;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\FileSystem\FilesystemAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\AbstractDependenciesChecker;
\defined( 'ABSPATH' ) || exit;
/**
 * Checks whether a list of WP Plugins is present or not.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Dependencies\Checkers
 */
class WPPluginsChecker extends AbstractDependenciesChecker {

	// region TRAITS
	use FilesystemAwareTrait;
	// endregion
	// region GETTERS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_type() : string {
		return 'active_plugins';
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_dependency( $dependency ) : bool {
		$validity = $this->is_dependency_valid( $dependency );
		if ( \true === $validity ) {
			$dependency['name']   = $this->get_plugin_name( $dependency['plugin'], $dependency );
			$this->dependencies[] = $dependency;
		}
		return $validity;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_missing_dependencies() : array {
		$missing = array();
		foreach ( $this->get_dependencies() as $dependency ) {
			$is_active = $this->is_plugin_active( $dependency['plugin'], $dependency );
			if ( ! $is_active ) {
				$missing[ $dependency['plugin'] ] = $dependency;
			} elseif ( isset( $dependency['min_version'] ) ) {
				$version = $this->get_active_plugin_version( $dependency['plugin'], $dependency );
				if ( \version_compare( $version, $dependency['min_version'], '<' ) ) {
					$missing[ $dependency['plugin'] ] = $dependency + array( 'version' => $version );
				}
			}
		}
		return $missing;
	}
	// endregion
	// region HELPERS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function is_dependency_valid( $dependency ) : bool {
		return \is_array( $dependency ) && Arrays::has_string_keys( $dependency ) && isset( $dependency['plugin'] );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_dependency_key() : string {
		return 'plugin';
	}
	/**
	 * Returns whether a given plugin is active or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $plugin             Plugin to check.
	 * @param   array   $plugin_config      Dependency configuration.
	 *
	 * @return  bool
	 */
	protected function is_plugin_active( string $plugin, array $plugin_config ) : bool {
		if ( isset( $plugin_config['active_checker'] ) && \is_callable( $plugin_config['active_checker'] ) ) {
			$is_active = Booleans::maybe_cast( \call_user_func( $plugin_config['active_checker'] ), \false );
		} else {
			$is_active = \in_array( $plugin, (array) \get_option( 'active_plugins', array() ), \true );
			if ( \is_multisite() && ! $is_active ) {
				$is_active = isset( \get_site_option( 'active_sitewide_plugins', array() )[ $plugin ] );
			}
		}
		return $is_active;
	}
	/**
	 * Returns the version of an installed and active plugin.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $plugin             Plugin to check.
	 * @param   array   $plugin_config      Dependency configuration.
	 *
	 * @return  string
	 */
	protected function get_active_plugin_version( string $plugin, array $plugin_config ) : string {
		if ( isset( $plugin_config['version_getter'] ) && \is_callable( $plugin_config['version_getter'] ) ) {
			$version = \call_user_func( $plugin_config['version_getter'] );
		} else {
			$plugin_data = $this->get_plugin_data( $plugin, array( 'version' => 'Version' ) );
			$version     = \is_null( $plugin_data ) ? '0.0.0' : ( $plugin_data['version'] ?: '0.0.0' );
		}
		return $version;
	}
	/**
	 * Returns a plugin's name.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $plugin             Plugin to check.
	 * @param   array   $plugin_config      Dependency configuration.
	 *
	 * @return  string
	 */
	protected function get_plugin_name( string $plugin, array $plugin_config ) : string {
		if ( $this->is_plugin_active( $plugin, $plugin_config ) ) {
			if ( isset( $plugin_config['name_getter'] ) && \is_callable( $plugin_config['name_getter'] ) ) {
				$plugin_name = Strings::maybe_cast( \call_user_func( $plugin_config['name_getter'] ), '' );
			} else {
				$plugin_data = $this->get_plugin_data(
					$plugin,
					array(
						'name'        => 'Plugin Name',
						'text_domain' => 'Text Domain',
					)
				);
				if ( ! \is_null( $plugin_data ) ) {
					$plugin_name = $plugin_data['name'] ?: $plugin_config['fallback_name'] ?? '';
					if ( ! empty( $plugin_data['text_domain'] ) ) {
						$plugin_name = \__( $plugin_name, $plugin_data['text_domain'] );
                        // phpcs:ignore WordPress.WP.I18n
					}
				} else {
					$plugin_name = $plugin_config['fallback_name'] ?? '';
				}
			}
		} else {
			$plugin_name = $plugin_config['fallback_name'] ?? '';
		}
		return $plugin_name;
	}
	/**
	 * Retrieves header data from a given plugin's file.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $plugin     The active plugin.
	 * @param   array   $headers    The headers to retrieve.
	 *
	 * @return  array|null
	 */
	protected function get_plugin_data( string $plugin, array $headers ) : ?array {
		$wp_filesystem = $this->get_wp_filesystem();
		return \is_null( $wp_filesystem ) ? null : \get_file_data( \trailingslashit( $wp_filesystem->wp_plugins_dir() ) . $plugin, $headers );
	}
	// endregion
}
