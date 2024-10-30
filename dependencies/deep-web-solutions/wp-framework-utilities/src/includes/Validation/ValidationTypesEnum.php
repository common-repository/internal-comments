<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation;

\defined( 'ABSPATH' ) || exit;
/**
 * Valid values for validation types.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Validation
 */
class ValidationTypesEnum {

	/**
	 * Validate a string value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const STRING = 'string';
	/**
	 * Validate an array value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const ARRAY = 'array';
	/**
	 * Validate a boolean value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const BOOLEAN = 'boolean';
	/**
	 * Validate an integer value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const INTEGER = 'integer';
	/**
	 * Validate a float value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const FLOAT = 'float';
	/**
	 * Validate a callable.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const CALLABLE = 'callable';
}
