<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Templating;

\defined( 'ABSPATH' ) || exit;
/**
 * Trait for working with the templating service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Templating
 */
trait TemplatingServiceAwareTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Templating service instance..
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     TemplatingService
	 */
	protected TemplatingService $templating_service;
	// endregion
	// region GETTERS
	/**
	 * Gets the current templating service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  TemplatingService
	 */
	public function get_templating_service() : TemplatingService {
		return $this->templating_service;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets a logging templating instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   TemplatingService       $templating_service     Templating service instance to use from now on.
	 */
	public function set_templating_service( TemplatingService $templating_service ) {
		$this->templating_service = $templating_service;
	}
	// endregion
}
