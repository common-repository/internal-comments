<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Checkers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\AbstractDependenciesChecker;
\defined( 'ABSPATH' ) || exit;
/**
 * Checks whether a list of PHP settings is compatible with the current environment or not.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Dependencies\Checkers
 */
class PHPIncompatibleSettingsChecker extends AbstractDependenciesChecker {

	// region GETTERS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_type() : string {
		return 'php_settings';
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 * @see     https://github.com/skyverge/wc-plugin-framework/blob/de7f429af153a17a0fd84cf9a1c56c6ac5ffbc08/woocommerce/class-sv-wc-plugin-dependencies.php
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function get_missing_dependencies() : array {
		$missing = array();
		if ( \function_exists( 'ini_get' ) ) {
			foreach ( $this->get_dependencies() as $dependency ) {
				$environment_value = \ini_get( $dependency['option_name'] );
				if ( empty( $environment_value ) ) {
					continue;
				}
				if ( \is_int( $dependency['expected_value'] ) ) {
					$is_size           = ! \is_numeric( \substr( $environment_value, -1 ) );
					$environment_value = $is_size ? Strings::letter_to_number( $environment_value ) : $environment_value;
					if ( $environment_value < $dependency['expected_value'] ) {
						$missing[ $dependency['option_name'] ] = array(
							'expected_value' => $is_size ? \size_format( $dependency['expected_value'] ) : $dependency['expected_value'],
							'environment'    => $is_size ? \size_format( $environment_value ) : $environment_value,
							'type'           => 'min',
						);
					}
				} elseif ( $environment_value !== $dependency['expected_value'] ) {
					$missing[ $dependency['option_name'] ] = array(
						'expected_value' => $dependency['expected_value'],
						'environment'    => $environment_value,
					);
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
		return \is_array( $dependency ) && Arrays::has_string_keys( $dependency ) && isset( $dependency['option_name'], $dependency['expected_value'] );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_dependency_key() : string {
		return 'option_name';
	}
	// endregion
}
