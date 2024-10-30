<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotImplementedException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
\defined( 'ABSPATH' ) || exit;
/**
 * A collection of useful helpers for working with dependencies.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
final class DependenciesServiceHelpers {

	/**
	 * Converts the result of a call to @see DependenciesHandlerInterface::are_dependencies_fulfilled to a simple boolean.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   bool|bool[]|bool[][]    $are_deps_fulfilled     The result of dependency checking.
	 * @param   bool                    $include_optional       Whether to include the result of optional dependencies or not.
	 *
	 * @return  bool
	 */
	public static function status_to_boolean( $are_deps_fulfilled, bool $include_optional = \true ) : bool {
		if ( \is_array( \reset( $are_deps_fulfilled ) ) ) {
			// MultiCheckerHandler
			foreach ( $are_deps_fulfilled as $deps_status ) {
				if ( \false === $include_optional ) {
					$optional_keys = Arrays::search_keys( $deps_status, \true, array( self::class, 'is_optional_checker' ) );
					foreach ( Arrays::validate( $optional_keys, array() ) as $optional_key ) {
						unset( $deps_status[ $optional_key ] );
					}
				}
				$unfulfilled = Arrays::search_values( $deps_status, \false, null, \false );
				if ( ! \is_null( $unfulfilled ) ) {
					$are_deps_fulfilled = \false;
					break;
				}
			}
			$are_deps_fulfilled = \false !== $are_deps_fulfilled;
		} elseif ( \is_array( $are_deps_fulfilled ) ) {
			// SingleCheckerHandler
			if ( \true === $include_optional ) {
				$are_deps_fulfilled = \reset( $are_deps_fulfilled );
			} else {
				$are_deps_fulfilled = \reset( $are_deps_fulfilled ) || self::is_optional_checker( \array_key_first( $are_deps_fulfilled ) );
			}
		}
		return $are_deps_fulfilled;
	}
	/**
	 * Returns whether a checker checks for optional dependencies or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $checker_id     The ID of the checker to look into.
	 *
	 * @return  bool
	 */
	public static function is_optional_checker( string $checker_id ) : bool {
		return \false !== \strpos( $checker_id, 'optional' );
	}
	/**
	 * Returns the message of the admin notice for missing WP plugins.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $registrant_name    The name of the component that requires the missing plugins.
	 * @param   array   $missing_plugins    List of missing WP plugins.
	 * @param   bool    $are_optional       Whether the plugins are optional or required.
	 *
	 * @return  string
	 */
	public static function get_missing_plugins_notice_message( string $registrant_name, array $missing_plugins, bool $are_optional = \false ) : string {
		if ( $are_optional ) {
			return \sprintf(
				/* translators: 1. Plugin or identifiable name, 2. Comma-separated list of missing PHP extensions. */
				\_n( '<strong>%1$s</strong> may behave unexpectedly because the %2$s plugin is either not installed or not active. Please install and activate the plugin first.', '<strong>%1$s</strong> may behave unexpectedly because the following plugins are either not installed or active: %2$s. Please install and activate these plugins first.', \count( $missing_plugins ), 'internal-comments' ),
				\esc_html( $registrant_name ),
				'<strong>' . self::format_missing_plugins_list( $missing_plugins ) . '</strong>'
			);
		} else {
			return \sprintf(
				/* translators: 1. Plugin or identifiable name, 2. Comma-separated list of missing PHP extensions. */
				\_n( '<strong>%1$s</strong> requires the %2$s plugin to be installed and active. Please install and activate the plugin first.', '<strong>%1$s</strong> requires the following plugins to be installed and active: %2$s. Please install and activate these plugins first.', \count( $missing_plugins ), 'internal-comments' ),
				\esc_html( $registrant_name ),
				'<strong>' . self::format_missing_plugins_list( $missing_plugins ) . '</strong>'
			);
		}
	}
	/**
	 * Formats the list of missing plugin dependencies in a human-friendly way.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $missing_plugins    List of missing plugin dependencies.
	 *
	 * @return  string
	 */
	public static function format_missing_plugins_list( array $missing_plugins ) : string {
		$plugin_names = array();
		foreach ( $missing_plugins as $missing_plugin ) {
			$plugin_name = $missing_plugin['name'];
			if ( isset( $missing_plugin['min_version'] ) ) {
				$plugin_name .= " {$missing_plugin['min_version']}+";
			}
			if ( isset( $missing_plugin['version'] ) ) {
				$formatted_version = \sprintf(
					/* translators: %s: Installed version of the dependant plugin */
					\__( 'You\'re running version %s', 'internal-comments' ),
					$missing_plugin['version']
				);
				$plugin_name .= ' <em>(' . \esc_html( $formatted_version ) . ')</em>';
			}
			$plugin_names[] = $plugin_name;
		}
		return \join( ', ', $plugin_names );
	}
	/**
	 * Returns the message of the admin notice for incompatible PHP settings.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $registrant_name    The name of the component that requires the missing PHP settings.
	 * @param   array   $php_settings       List of incompatible PHP settings.
	 * @param   bool    $are_optional       Whether the settings are optional or required.
	 *
	 * @return  string
	 */
	public static function get_incompatible_php_settings_notice_message( string $registrant_name, array $php_settings, bool $are_optional = \false ) : string {
		if ( $are_optional ) {
			$message = \sprintf(
				/* translators: Plugin name or identifiable name. */
				\__( '<strong>%s</strong> may behave unexpectedly because the following PHP configuration settings are expected:', 'internal-comments' ),
				\esc_html( $registrant_name )
			) . '<ul>';
			$message .= self::format_incompatible_settings_list( $php_settings );
			$message .= '</ul>' . \__( 'Please contact your hosting provider or server administrator to configure these settings. The plugin will attempt to run despite this warning.', 'internal-comments' );
		} else {
			$message = \sprintf(
				/* translators: Plugin name or identifiable name. */
				\__( '<strong>%s</strong> cannot run because the following PHP configuration settings are expected:', 'internal-comments' ),
				\esc_html( $registrant_name )
			) . '<ul>';
			$message .= self::format_incompatible_settings_list( $php_settings );
			$message .= '</ul>' . \__( 'Please contact your hosting provider or server administrator to configure these settings.', 'internal-comments' );
		}
		return $message;
	}
	/**
	 * Formats the list of incompatible PHP settings in a human-friendly way.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $php_settings   List of incompatible settings dependencies.
	 *
	 * @return  string
	 */
	public static function format_incompatible_settings_list( array $php_settings ) : string {
		$message = '';
		foreach ( $php_settings as $setting => $values ) {
			$setting_message = "<code>{$setting} = {$values['expected']}</code>";
			if ( ! empty( $values['type'] ) ) {
				switch ( $values['type'] ) {
					case 'min':
						$setting_message = \sprintf(
							/* translators: PHP settings value. */
							\__( '%s or higher', 'internal-comments' ),
							$setting_message
						);
						break;
				}
			}
			$message .= "<li>{$setting_message}</li>";
		}
		return $message;
	}
	/**
	 * Returns the message of the admin notice for missing PHP functions.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $registrant_name    The name of the component that requires the missing PHP functions.
	 * @param   array   $php_functions      List of missing PHP functions.
	 * @param   bool    $are_optional        Whether the functions are optional or required.
	 *
	 * @return  string
	 */
	public static function get_missing_php_functions_notice_message( string $registrant_name, array $php_functions, bool $are_optional = \false ) : string {
		if ( $are_optional ) {
			return \sprintf(
				/* translators: 1. Plugin or identifiable name, 2. Comma-separated list of missing PHP functions. */
				\_n( '<strong>%1$s</strong> may behave unexpectedly because the %2$s PHP function is missing. Contact your host or server administrator to install and configure the missing function.', '<strong>%1$s</strong> may behave unexpectedly because the following PHP functions are missing: %2$s. Contact your host or server administrator to install and configure the missing functions.', \count( $php_functions ), 'internal-comments' ),
				\esc_html( $registrant_name ),
				'<strong>' . \implode( ', ', $php_functions ) . '</strong>'
			);
		} else {
			return \sprintf(
				/* translators: 1. Plugin or identifiable name, 2. Comma-separated list of missing PHP functions. */
				\_n( '<strong>%1$s</strong> requires the %2$s PHP function to exist. Contact your host or server administrator to install and configure the missing function.', '<strong>%1$s</strong> requires the following PHP functions to exist: %2$s. Contact your host or server administrator to install and configure the missing functions.', \count( $php_functions ), 'internal-comments' ),
				\esc_html( $registrant_name ),
				'<strong>' . \implode( ', ', $php_functions ) . '</strong>'
			);
		}
	}
	/**
	 * Returns the message of the admin notice for missing PHP extensions.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $registrant_name    The name of the component that requires the missing PHP extensions.
	 * @param   array   $php_extensions     List of missing PHP extensions.
	 * @param   bool    $are_optional       Whether the extensions are optional or required.
	 *
	 * @return  string
	 */
	public static function get_missing_php_extensions_notice_message( string $registrant_name, array $php_extensions, bool $are_optional = \false ) : string {
		if ( $are_optional ) {
			return \sprintf(
				/* translators: 1. Plugin or identifiable name, 2. Comma-separated list of missing PHP extensions. */
				\_n( '<strong>%1$s</strong> may behave unexpectedly because the %2$s PHP extension is missing. Contact your host or server administrator to install and configure the missing extension.', '<strong>%1$s</strong> may behave unexpectedly because the following PHP extensions are missing: %2$s. Contact your host or server administrator to install and configure the missing extensions.', \count( $php_extensions ), 'internal-comments' ),
				\esc_html( $registrant_name ),
				'<strong>' . \implode( ', ', $php_extensions ) . '</strong>'
			);
		} else {
			return \sprintf(
				/* translators: 1. Plugin or identifiable name, 2. Comma-separated list of missing PHP extensions. */
				\_n( '<strong>%1$s</strong> requires the %2$s PHP extension to function. Contact your host or server administrator to install and configure the missing extension.', '<strong>%1$s</strong> requires the following PHP extensions to function: %2$s. Contact your host or server administrator to install and configure the missing extensions.', \count( $php_extensions ), 'internal-comments' ),
				\esc_html( $registrant_name ),
				'<strong>' . \implode( ', ', $php_extensions ) . '</strong>'
			);
		}
	}
	/**
	 * Returns the admin notice message to display for the missing dependencies.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $type               Dependencies type.
	 * @param   string  $registrant_name    The name of the component that requires the missing dependencies.
	 * @param   array   $unfulfilled_deps   The list of missing dependencies.
	 * @param   bool    $are_optional       Whether the dependencies are optional or not.
	 *
	 * @throws  NotImplementedException     Thrown when the message type is not supported.
	 *
	 * @return  string
	 */
	public static function get_missing_dependencies_notice_message( string $type, string $registrant_name, array $unfulfilled_deps, bool $are_optional = \false ) : string {
		switch ( $type ) {
			case 'php_extensions':
				$message = self::get_missing_php_extensions_notice_message( $registrant_name, $unfulfilled_deps, $are_optional );
				break;
			case 'php_functions':
				$message = self::get_missing_php_functions_notice_message( $registrant_name, $unfulfilled_deps, $are_optional );
				break;
			case 'php_settings':
				$message = self::get_incompatible_php_settings_notice_message( $registrant_name, $unfulfilled_deps, $are_optional );
				break;
			case 'active_plugins':
				$message = self::get_missing_plugins_notice_message( $registrant_name, $unfulfilled_deps, $are_optional );
				break;
			default:
				throw new NotImplementedException( 'Dependencies admin notices type not supported' );
		}
		return $message;
	}
}
