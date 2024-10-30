<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\InexistentPropertyException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotSupportedException;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the validation-service-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Validation
 */
trait ValidationServiceAwareTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Validation service for registering filters and actions.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     ValidationService
	 */
	protected ValidationService $validation_service;
	// endregion
	// region GETTERS
	/**
	 * Gets the current validation service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ValidationService
	 */
	public function get_validation_service() : ValidationService {
		return $this->validation_service;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets a validation service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ValidationService       $validation_service         Validation service instance to use from now on.
	 */
	public function set_validation_service( ValidationService $validation_service ) {
		$this->validation_service = $validation_service;
	}
	// endregion
	// region METHODS
	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The key inside the container.
	 * @param   string  $handler_id     The ID of the handler to retrieve the value from.
	 *
	 * @return  InexistentPropertyException|mixed
	 */
	protected function get_default_value( string $key, string $handler_id = 'settings' ) {
		return $this->get_validation_service()->get_handler( $handler_id )->get_default_value( $key );
	}
	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key            The key inside the container.
	 * @param   string  $handler_id     The ID of the handler to retrieve the value from.
	 *
	 * @return  InexistentPropertyException|array
	 */
	protected function get_supported_options( string $key, string $handler_id = 'settings' ) {
		return $this->get_validation_service()->get_handler( $handler_id )->get_supported_options( $key );
	}
	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value              The value to validate.
	 * @param   string  $default_key        The key of the default value in the container.
	 * @param   string  $validation_type    The type of validation to perform. Valid values are listed in the ValidationTypesEnum class.
	 * @param   string  $handler_id         The ID of the handler to validate against.
	 *
	 * @throws  NotSupportedException   Thrown if the validation type requested is not supported.
	 *
	 * @return  array|bool|callable|float|int|string
	 */
	protected function validate_value( $value, string $default_key, string $validation_type, string $handler_id = 'settings' ) {
		return $this->get_validation_service()->validate_value( $value, $default_key, $validation_type, $handler_id );
	}
	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value              The value to validate.
	 * @param   string  $default_key        The key of the default value in the within the handler.
	 * @param   string  $options_key        The key of the supported options within the handler.
	 * @param   string  $validation_type    The type of validation to perform. Valid values are listed in the ValidationTypesEnum class.
	 * @param   string  $handler_id         The ID of the handler to use for validation.
	 *
	 * @return  array|string
	 */
	protected function validate_allowed_value( $value, string $default_key, string $options_key, string $validation_type, string $handler_id = 'settings' ) {
		return $this->get_validation_service()->validate_allowed_value( $value, $default_key, $options_key, $validation_type, $handler_id );
	}
	// endregion
}
