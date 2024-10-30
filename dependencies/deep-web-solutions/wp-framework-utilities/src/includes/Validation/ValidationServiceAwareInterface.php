<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a validation-service-aware instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Validation
 */
interface ValidationServiceAwareInterface {

	/**
	 * Gets the current validation service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ValidationService
	 */
	public function get_validation_service() : ValidationService;
	/**
	 * Sets a validation service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ValidationService       $validation_service         Validation service instance to use from now on.
	 */
	public function set_validation_service( ValidationService $validation_service);
}
