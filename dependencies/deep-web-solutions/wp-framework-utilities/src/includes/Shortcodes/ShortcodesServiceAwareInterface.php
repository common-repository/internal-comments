<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a shortcodes-service-aware instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Shortcodes
 */
interface ShortcodesServiceAwareInterface {

	/**
	 * Gets the current shortcodes service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ShortcodesService
	 */
	public function get_shortcodes_service() : ShortcodesService;
	/**
	 * Sets a shortcodes service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ShortcodesService       $shortcodes_service     Shortcodes service instance to use from now on.
	 */
	public function set_shortcodes_service( ShortcodesService $shortcodes_service);
}
