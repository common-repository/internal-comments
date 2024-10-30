<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes;

\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of a shortcodes-service-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Shortcodes
 */
trait ShortcodesServiceAwareTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Shortcodes service for registering shortcodes.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     ShortcodesService
	 */
	protected ShortcodesService $shortcodes_service;
	// endregion
	// region GETTERS
	/**
	 * Gets the current shortcodes service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ShortcodesService
	 */
	public function get_shortcodes_service() : ShortcodesService {
		return $this->shortcodes_service;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets a shortcodes service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ShortcodesService       $shortcodes_service         Shortcodes service instance to use from now on.
	 */
	public function set_shortcodes_service( ShortcodesService $shortcodes_service ) {
		$this->shortcodes_service = $shortcodes_service;
	}
	// endregion
}
