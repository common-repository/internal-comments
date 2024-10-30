<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions;

\defined( 'ABSPATH' ) || exit;
/**
 * An exception thrown when trying to call a method or access a code branch that hasn't been implemented.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Exceptions
 */
class NotImplementedException extends \LogicException {

	/* empty on purpose */
}
