<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions;

\defined( 'ABSPATH' ) || exit;
/**
 * An exception thrown when trying to call an method that is not supported by the instance. Example scenarios include
 * an adapter which can't translate an action because the target API doesn't support the command.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Exceptions
 */
class NotSupportedException extends \LogicException {

	/* empty on purpose */
}
