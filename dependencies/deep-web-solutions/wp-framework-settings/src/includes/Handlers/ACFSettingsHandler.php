<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\AbstractSettingsHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Adapters\ACFSettingsAdapter;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsActionsEnum;
\defined( 'ABSPATH' ) || exit;
/**
 * Handles the interoperability layer between the DWS framework and the ACF settings framework.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class ACFSettingsHandler extends AbstractSettingsHandler {

	// region MAGIC METHODS
	/**
	 * ACF Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                      $handler_id     The ID of the settings handler.
	 * @param   ACFSettingsAdapter|null     $adapter        Instance of the adapter to the ACF settings framework.
	 */
	public function __construct( string $handler_id = 'acf', ?ACFSettingsAdapter $adapter = null ) {
		parent::__construct( $handler_id, $adapter ?? new ACFSettingsAdapter() );
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
			case SettingsActionsEnum::REGISTER_FIELD:
				return 'acf/include_fields';
			default:
				return 'acf/init';
		}
	}
	// endregion
}
