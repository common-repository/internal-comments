<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an admin notices service utility instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices
 */
interface AdminNoticesServiceRegisterInterface {

	/**
	 * Using classes should define their admin notices in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   AdminNoticesService     $notices_service        Instance of the admin notices service.
	 */
	public function register_admin_notices( AdminNoticesService $notices_service) : void;
}
