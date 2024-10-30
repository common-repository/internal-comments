<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\InexistentPropertyException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Booleans;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Callables;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Floats;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Integers;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating some of the most often needed functionality of a validation handler.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Validation
 */
abstract class AbstractValidationHandler extends AbstractHandler implements ValidationHandlerInterface {

	// region MAGIC METHODS
	/**
	 * AbstractValidationHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $handler_id     The ID of the handler instance.
	 */
	public function __construct( string $handler_id = 'settings' ) {
        // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		parent::__construct( $handler_id );
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_type() : string {
		return 'validation';
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function validate_string( $value, string $key ) : string {
		$default = $this->get_default_value_or_throw( $key );
		$default = Strings::validate( $default, Strings::maybe_cast( $default, '' ) );
		return Strings::maybe_cast( $value, $default );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function validate_allowed_string( $value, string $default_key, string $options_key ) : string {
		$value   = $this->validate_string( $value, $default_key );
		$options = $this->get_supported_options_or_throw( $options_key );
		$result  = Strings::validate_allowed( $value, $options );
		if ( \is_null( $result ) && Arrays::has_string_keys( $options ) ) {
			$result = Strings::validate_allowed( $value, \array_keys( $options ) );
		}
		return $this->validate_string( $result, $default_key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function validate_array( $value, string $key ) : array {
		$default = $this->get_default_value_or_throw( $key );
		$default = Arrays::validate( $default, Arrays::maybe_cast( $default, array() ) );
		return Arrays::maybe_cast( $value, $default );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function validate_allowed_array( $value, string $default_key, string $options_key ) : array {
		if ( \is_null( Arrays::validate( $value ) ) ) {
			return $this->validate_array( null, $default_key );
		}
		$options = $this->get_supported_options_or_throw( $options_key );
		$result  = \array_filter( Arrays::validate_allowed( $value, $options, \false ) );
		if ( empty( $result ) && ! Arrays::is_list( $options ) ) {
			$result = \array_filter( Arrays::validate_allowed( $value, \array_keys( $options ), \false ) );
		}
		return $result;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function validate_boolean( $value, string $key ) : bool {
		$default = $this->get_default_value_or_throw( $key );
		$default = Booleans::validate( $default, Booleans::maybe_cast( $default, \false ) );
		return Booleans::maybe_cast( $value, $default );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function validate_integer( $value, string $key ) : int {
		$default = $this->get_default_value_or_throw( $key );
		$default = Integers::validate( $default, Integers::maybe_cast( $default, 0 ) );
		return Integers::maybe_cast( $value, $default );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function validate_float( $value, string $key ) : float {
		$default = $this->get_default_value_or_throw( $key );
		$default = Floats::validate( $default, Floats::maybe_cast( $default, 0.0 ) );
		return Floats::maybe_cast( $value, $default );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function validate_callable( $value, string $key ) : callable {
		$default = $this->get_default_value_or_throw( $key );
		$default = Callables::validate( $default, fn( $value) => $value );
		return Callables::validate( $value, $default );
	}
	// endregion
	// region HELPERS
	/**
	 * Retrieves the default value for a given key or throws the exception if not found.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The key inside the container.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value or the supported values were not found inside the containers.
	 *
	 * @return  mixed
	 */
	protected function get_default_value_or_throw( string $key ) {
		$default = $this->get_default_value( $key );
		if ( $default instanceof InexistentPropertyException ) {
			throw $default;
		}
		return $default;
	}
	/**
	 * Retrieves the supported options for a given key or throws the exception if not found.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The key inside the container.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value or the supported values were not found inside the containers.
	 *
	 * @return  array
	 */
	protected function get_supported_options_or_throw( string $key ) : array {
		$options = $this->get_supported_options( $key );
		if ( $options instanceof InexistentPropertyException ) {
			throw $options;
		}
		return $options;
	}
	// endregion
}
