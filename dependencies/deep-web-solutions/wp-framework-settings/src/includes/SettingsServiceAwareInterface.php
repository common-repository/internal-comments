<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a settings-service-aware instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
interface SettingsServiceAwareInterface {

	/**
	 * Gets the current settings service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  SettingsService
	 */
	public function get_settings_service() : SettingsService;
	/**
	 * Sets a settings service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsService     $service        Settings service instance to use from now on.
	 */
	public function set_settings_service( SettingsService $service);
}
