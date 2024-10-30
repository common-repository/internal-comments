<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance of a shortcodes handler compatible with the shortcodes service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Shortcodes
 */
interface ShortcodesHandlerInterface extends HandlerInterface, ShortcodesAdapterInterface {

	/* empty on purpose */
}
