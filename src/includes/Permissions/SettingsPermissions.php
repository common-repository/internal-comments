<?php

namespace DeepWebSolutions\Plugins\InternalComments\Permissions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Core\Functionalities\AbstractPermissionsChildFunctionality;

/**
 * Collection of permissions used by the settings portion of the plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class SettingsPermissions extends AbstractPermissionsChildFunctionality {
	// region PERMISSION CONSTANTS

	/**
	 * Capability needed to view the settings page and update the options.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const MANAGE_OPTIONS = 'manage_dws_internal_comments_options';

	// endregion
}
