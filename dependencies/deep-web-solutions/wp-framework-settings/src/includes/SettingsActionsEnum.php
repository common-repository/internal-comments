<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings;

\defined( 'ABSPATH' ) || exit;
/**
 * Valid values for settings hooks contexts.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
class SettingsActionsEnum {

	/**
	 * Denotes that an admin menu page is to be registered.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const REGISTER_MENU_PAGE = 'register_menu_page';
	/**
	 * Denotes that an admin submenu page is to be registered.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const REGISTER_SUBMENU_PAGE = 'register_submenu_page';
	/**
	 * Denotes that an admin options group is to be registered.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const REGISTER_OPTIONS_GROUP = 'register_options_group';
	/**
	 * Denotes that an admin generic group is to be registered.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const REGISTER_GENERIC_GROUP = 'register_generic_group';
	/**
	 * Denotes that an admin setting field is to be registered.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const REGISTER_FIELD = 'register_field';
	/**
	 * Returns a list of all valid actions.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string[]
	 */
	public static function get_all() : array {
		return array( self::REGISTER_MENU_PAGE, self::REGISTER_SUBMENU_PAGE, self::REGISTER_OPTIONS_GROUP, self::REGISTER_GENERIC_GROUP, self::REGISTER_FIELD );
	}
}
