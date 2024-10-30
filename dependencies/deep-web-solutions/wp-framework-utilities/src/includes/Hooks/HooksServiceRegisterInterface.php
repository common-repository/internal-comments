<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a hooks service utility instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks
 */
interface HooksServiceRegisterInterface {

	/**
	 * Using classes should define their hooks in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HooksService    $hooks_service      Instance of the hooks service.
	 */
	public function register_hooks( HooksService $hooks_service) : void;
}
