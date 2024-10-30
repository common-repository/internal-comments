<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks;

\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the hooks-service-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks
 */
trait HooksServiceAwareTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Hooks service for registering filters and actions.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     HooksService
	 */
	protected HooksService $hooks_service;
	// endregion
	// region GETTERS
	/**
	 * Gets the hooks service instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  HooksService
	 */
	public function get_hooks_service() : HooksService {
		return $this->hooks_service;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets the hooks service.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HooksService    $hooks_service      Instance of the hooks service.
	 */
	public function set_hooks_service( HooksService $hooks_service ) {
		$this->hooks_service = $hooks_service;
	}
	// endregion
}
