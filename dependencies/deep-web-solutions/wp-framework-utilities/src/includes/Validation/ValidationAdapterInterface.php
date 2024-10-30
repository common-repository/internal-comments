<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\InexistentPropertyException;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes the compatibility layer between the framework and a validation provider.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Validation
 */
interface ValidationAdapterInterface {

	/**
	 * Validates a given value as a string.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value  The value to validate.
	 * @param   string  $key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value was not found.
	 *
	 * @return  string
	 */
	public function validate_string( $value, string $key) : string;
	/**
	 * Validates a given value against a list of supported options.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value          The value to validate.
	 * @param   string  $default_key    The composite key to retrieve the default value.
	 * @param   string  $options_key    The composite key to retrieve the supported options.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value or the supported options were not found.
	 *
	 * @return  string
	 */
	public function validate_allowed_string( $value, string $default_key, string $options_key) : string;
	/**
	 * Validates a given value as an array.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value  The value to validate.
	 * @param   string  $key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value was not found.
	 *
	 * @return  array
	 */
	public function validate_array( $value, string $key) : array;
	/**
	 * Validates an array of values against a list of supported options. Returns a new array containing only valid entries.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value          The value to validate.
	 * @param   string  $default_key    The composite key to retrieve the default value.
	 * @param   string  $options_key    The composite key to retrieve the supported options.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value or the supported options were not found.
	 *
	 * @return  array
	 */
	public function validate_allowed_array( $value, string $default_key, string $options_key) : array;
	/**
	 * Validates a given value as a boolean.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value  The value to validate.
	 * @param   string  $key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value was not found.
	 *
	 * @return  bool
	 */
	public function validate_boolean( $value, string $key) : bool;
	/**
	 * Validates a given value as an int.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value  The value to validate.
	 * @param   string  $key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value was not found.
	 *
	 * @return  int
	 */
	public function validate_integer( $value, string $key) : int;
	/**
	 * Validates a given value as a float.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value  The value to validate.
	 * @param   string  $key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value was not found.
	 *
	 * @return  float
	 */
	public function validate_float( $value, string $key) : float;
	/**
	 * Validates a given value as a callable.
	 *
	 * @param   mixed   $value  The value to validate.
	 * @param   string  $key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value was not found.
	 *
	 * @return  callable
	 */
	public function validate_callable( $value, string $key) : callable;
}
