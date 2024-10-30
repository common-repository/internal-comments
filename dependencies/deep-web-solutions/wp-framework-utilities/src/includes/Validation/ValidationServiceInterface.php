<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotSupportedException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\ServiceInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance of a validation service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Validation
 */
interface ValidationServiceInterface extends ServiceInterface, ValidationAdapterInterface {

	/**
	 * Validates a value based on passed parameters.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value              The value to validate.
	 * @param   string  $default_key        The key of the default value in the within the handler.
	 * @param   string  $validation_type    The type of validation to perform. Valid values are listed in the ValidationTypesEnum class.
	 * @param   string  $handler_id         The ID of the handler to use for validation.
	 *
	 * @throws  NotSupportedException   Thrown if the validation type requested is not supported.
	 *
	 * @return  array|bool|callable|float|int|string
	 */
	public function validate_value( $value, string $default_key, string $validation_type, string $handler_id);
	/**
	 * Validates a value based on passed parameters.
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
	 * @throws  NotSupportedException   Thrown if the validation type requested is not supported.
	 *
	 * @return  string|array
	 */
	public function validate_allowed_value( $value, string $default_key, string $options_key, string $validation_type, string $handler_id);
}
