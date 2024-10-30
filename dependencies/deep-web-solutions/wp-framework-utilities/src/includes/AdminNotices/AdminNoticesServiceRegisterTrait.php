<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices;

use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Helpers\AdminNoticesHelpersTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the admin notices service register interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices
 */
trait AdminNoticesServiceRegisterTrait {

	// region TRAITS
	use AdminNoticesHelpersTrait;
	// endregion
	// region METHODS
	/**
	 * Using classes should define their admin notices in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   AdminNoticesService     $notices_service        Instance of the admin notices service.
	 */
	abstract public function register_admin_notices( AdminNoticesService $notices_service) : void;
	// endregion
}
