<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\AbstractAdminNoticesHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Notices\SimpleAdminNotice;
\defined( 'ABSPATH' ) || exit;
/**
 * Handles simple notices.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices\Handlers
 */
class SimpleNoticesHandler extends AbstractAdminNoticesHandler {

	// region INHERITED METHODS
	/**
	 * Returns the ID of the handler as the simple notice model class. Since there should be only one handler per type
	 * of admin notices, this is safe.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_id() : string {
		return SimpleAdminNotice::class;
	}
	// endregion
}
