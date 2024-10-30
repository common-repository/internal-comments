<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a shortcodes service register instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Shortcodes
 */
interface ShortcodesServiceRegisterInterface {

	/**
	 * Using classes should define their shortcodes in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ShortcodesService   $shortcodes_service     Instance of the shortcodes service.
	 */
	public function register_shortcodes( ShortcodesService $shortcodes_service) : void;
}
