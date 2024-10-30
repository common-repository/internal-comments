<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Functionalities;

use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation\Actions\InitializeValidationServiceTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation\ValidationServiceAwareInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for standardizing the registration and retrieval of validated options.
 *
 * @SuppressWarnings(PHPMD.LongClassName)
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Functionalities
 */
abstract class AbstractValidatedOptionsPageFunctionality extends AbstractOptionsPageFunctionality implements ValidationServiceAwareInterface {

	// region TRAITS
	use InitializeValidationServiceTrait;
	// endregion
	// region METHODS
	/**
	 * Attempts to return the validated value of a given field.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id   The ID of the field to retrieve prefixed by the group_name and a forward slash.
	 *
	 * @return  mixed|null
	 */
	public function get_validated_option_value( string $field_id ) {
		return \apply_filters( $this->get_hook_tag( 'get_validated_option_value' ), null, $field_id );
	}
	/**
	 * Attempts to validate a given value assuming it belongs to a given options field.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value      The value to validate.
	 * @param   string  $field_id   The ID of the field to assume the value belongs to, prefixed by the group_name and a forward slash.
	 *
	 * @return  mixed
	 */
	public function validate_option_value( $value, string $field_id ) {
		return \apply_filters( $this->get_hook_tag( 'validate_option_value' ), $value, $field_id, $value );
	}
	// endregion
}
