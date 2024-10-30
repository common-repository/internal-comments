<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an admin-notices-service-aware instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices
 */
interface AdminNoticesServiceAwareInterface {

	/**
	 * Gets the current admin notices service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  AdminNoticesService
	 */
	public function get_admin_notices_service() : AdminNoticesService;
	/**
	 * Sets an admin notices service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   AdminNoticesService     $notices_service    Admin notices service instance to use from now on.
	 */
	public function set_admin_notices_service( AdminNoticesService $notices_service);
}
