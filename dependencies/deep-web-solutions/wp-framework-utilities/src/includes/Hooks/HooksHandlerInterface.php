<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance of a hooks handler compatible with the hooks service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks
 */
interface HooksHandlerInterface extends HandlerInterface, HooksAdapterInterface {

	/* empty on purpose */
}
