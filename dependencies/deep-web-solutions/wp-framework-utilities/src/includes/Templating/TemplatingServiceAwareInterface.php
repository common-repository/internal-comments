<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Templating;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a templating-service-aware instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Templating
 */
interface TemplatingServiceAwareInterface {

	/**
	 * Gets the current templating service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  TemplatingService
	 */
	public function get_templating_service() : TemplatingService;
	/**
	 * Sets a logging templating instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   TemplatingService       $templating_service     Templating service instance to use from now on.
	 */
	public function set_templating_service( TemplatingService $templating_service);
}
