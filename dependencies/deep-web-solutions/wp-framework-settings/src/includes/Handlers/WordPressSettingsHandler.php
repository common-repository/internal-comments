<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\AbstractSettingsHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Adapters\WordPressSettingsAdapter;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsActionsEnum;
\defined( 'ABSPATH' ) || exit;
/**
 * Handles the interoperability layer between the DWS framework and the WordPress Settings API.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class WordPressSettingsHandler extends AbstractSettingsHandler {

	// region MAGIC METHODS
	/**
	 * WordPress Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                          $handler_id     The ID of the settings handler.
	 * @param   WordPressSettingsAdapter|null   $adapter        Instance of the adapter to the WordPress Settings API.
	 */
	public function __construct( string $handler_id = 'WordPress', ?WordPressSettingsAdapter $adapter = null ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		parent::__construct( $handler_id, $adapter ?? new WordPressSettingsAdapter() );
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_action_hook( string $context ) : string {
		switch ( $context ) {
			case SettingsActionsEnum::REGISTER_MENU_PAGE:
			case SettingsActionsEnum::REGISTER_SUBMENU_PAGE:
				return 'admin_menu';
			case SettingsActionsEnum::REGISTER_OPTIONS_GROUP:
			case SettingsActionsEnum::REGISTER_FIELD:
				return 'admin_init';
			case SettingsActionsEnum::REGISTER_GENERIC_GROUP:
				return 'add_meta_boxes';
			default:
				return 'init';
		}
	}
	// endregion
}
