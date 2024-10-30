<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings;

\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the settings service register interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
trait SettingsServiceRegisterTrait {

	/**
	 * Using classes should define their settings in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsService     $settings_service   Instance of the settings service.
	 */
	abstract public function register_settings( SettingsService $settings_service) : void;
}
