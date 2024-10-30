<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes a handler for a settings framework.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
interface SettingsHandlerInterface extends HandlerInterface, SettingsAdapterInterface {

	/**
	 * Returns the settings adapter used.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  SettingsAdapterInterface
	 */
	public function get_adapter() : SettingsAdapterInterface;
	/**
	 * Returns the hook on which the settings framework is ready to be used.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $context    The settings action that is to be performed.
	 *
	 * @return  string
	 */
	public function get_action_hook( string $context) : string;
}
