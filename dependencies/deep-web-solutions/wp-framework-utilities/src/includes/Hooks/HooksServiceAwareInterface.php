<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a hooks-service-aware instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks
 */
interface HooksServiceAwareInterface {

	/**
	 * Gets the current hooks service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  HooksService
	 */
	public function get_hooks_service() : HooksService;
	/**
	 * Sets a hooks service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HooksService    $hooks_service      Hooks service instance to use from now on.
	 */
	public function set_hooks_service( HooksService $hooks_service);
}
