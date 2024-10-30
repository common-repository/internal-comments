<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices;

\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the admin-notices-service-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices
 */
trait AdminNoticesServiceAwareTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Admin notices service for outputting admin notices.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     AdminNoticesService
	 */
	protected AdminNoticesService $admin_notices_service;
	// endregion
	// region GETTERS
	/**
	 * Gets the current admin notices service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  AdminNoticesService
	 */
	public function get_admin_notices_service() : AdminNoticesService {
		return $this->admin_notices_service;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets an admin notices service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   AdminNoticesService     $notices_service    Admin notices service instance to use from now on.
	 */
	public function set_admin_notices_service( AdminNoticesService $notices_service ) {
		$this->admin_notices_service = $notices_service;
	}
	// endregion
}
