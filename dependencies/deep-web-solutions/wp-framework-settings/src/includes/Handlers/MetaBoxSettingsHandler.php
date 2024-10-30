<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\AbstractSettingsHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Adapters\MetaBoxSettingsAdapter;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsActionsEnum;
\defined( 'ABSPATH' ) || exit;
/**
 * Handles the interoperability layer between the DWS framework and the Meta Box settings framework.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class MetaBoxSettingsHandler extends AbstractSettingsHandler {

	// region MAGIC METHODS
	/**
	 * Meta Box Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                          $handler_id     The ID of the settings handler.
	 * @param   MetaBoxSettingsAdapter|null     $adapter        Instance of the adapter to the Meta Box settings framework.
	 */
	public function __construct( string $handler_id = 'meta-box', ?MetaBoxSettingsAdapter $adapter = null ) {
		parent::__construct( $handler_id, $adapter ?? new MetaBoxSettingsAdapter() );
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
			case SettingsActionsEnum::REGISTER_OPTIONS_GROUP:
				return 'mb_settings_page_load';
			default:
				return 'init';
		}
	}
	// endregion
}
