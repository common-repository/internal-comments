<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotSupportedException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractMultiHandlerService;
\defined( 'ABSPATH' ) || exit;
/**
 * Performs various data validation actions against values defined in various given handlers.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Validation
 */
class ValidationService extends AbstractMultiHandlerService implements ValidationServiceInterface {

	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_handler( string $handler_id ) : ?ValidationHandlerInterface {
        // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_handler( $handler_id );
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 *
	 * @throws  NotSupportedException   Thrown when the validation type is unsupported.
	 */
	public function validate_value( $value, string $default_key, string $validation_type, string $handler_id = 'settings' ) {
		switch ( $validation_type ) {
			case ValidationTypesEnum::STRING:
				return $this->validate_string( $value, $default_key, $handler_id );
			case ValidationTypesEnum::ARRAY:
				return $this->validate_array( $value, $default_key, $handler_id );
			case ValidationTypesEnum::BOOLEAN:
				return $this->validate_boolean( $value, $default_key, $handler_id );
			case ValidationTypesEnum::INTEGER:
				return $this->validate_integer( $value, $default_key, $handler_id );
			case ValidationTypesEnum::FLOAT:
				return $this->validate_float( $value, $default_key, $handler_id );
			case ValidationTypesEnum::CALLABLE:
				return $this->validate_callable( $value, $default_key, $handler_id );
		}
		throw new NotSupportedException( 'Validation type not supported' );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotSupportedException   Thrown when the validation type is unsupported.
	 */
	public function validate_allowed_value( $value, string $default_key, string $options_key, string $validation_type, string $handler_id = 'settings' ) {
		switch ( $validation_type ) {
			case ValidationTypesEnum::STRING:
				return $this->validate_allowed_string( $value, $default_key, $options_key, $handler_id );
			case ValidationTypesEnum::ARRAY:
				return $this->validate_allowed_array( $value, $default_key, $options_key, $handler_id );
		}
		throw new NotSupportedException( 'Validation type not supported' );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function validate_string( $value, string $key, string $handler_id = 'settings' ) : string {
		return $this->get_handler( $handler_id )->validate_string( $value, $key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function validate_allowed_string( $value, string $default_key, string $options_key, string $handler_id = 'settings' ) : string {
		return $this->get_handler( $handler_id )->validate_allowed_string( $value, $default_key, $options_key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function validate_array( $value, string $key, string $handler_id = 'settings' ) : array {
		return $this->get_handler( $handler_id )->validate_array( $value, $key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function validate_allowed_array( $value, string $default_key, string $options_key, string $handler_id = 'settings' ) : array {
		return $this->get_handler( $handler_id )->validate_allowed_array( $value, $default_key, $options_key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function validate_boolean( $value, string $key, string $handler_id = 'settings' ) : bool {
		return $this->get_handler( $handler_id )->validate_boolean( $value, $key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function validate_integer( $value, string $key, string $handler_id = 'settings' ) : int {
		return $this->get_handler( $handler_id )->validate_integer( $value, $key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function validate_float( $value, string $key, string $handler_id = 'settings' ) : float {
		return $this->get_handler( $handler_id )->validate_float( $value, $key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function validate_callable( $value, string $key, string $handler_id = 'settings' ) : callable {
		return $this->get_handler( $handler_id )->validate_callable( $value, $key );
	}
	// endregion
	// region HELPERS
	/**
	 * {@inheritDoc}
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	protected function get_handler_class() : string {
		return ValidationHandlerInterface::class;
	}
	// endregion
}
