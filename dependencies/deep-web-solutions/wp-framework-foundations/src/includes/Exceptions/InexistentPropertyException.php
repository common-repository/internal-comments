<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions;

\defined( 'ABSPATH' ) || exit;
/**
 * An exception thrown when trying to access or manipulate an inexistent property.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Exceptions
 */
class InexistentPropertyException extends \InvalidArgumentException {

	/* empty on purpose */
}
