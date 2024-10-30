<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a caching-service-aware instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Caching
 */
interface CachingServiceAwareInterface {

	/**
	 * Gets the current caching service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  CachingService
	 */
	public function get_caching_service() : CachingService;
	/**
	 * Sets a caching service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   CachingService       $service        Caching service instance to use from now on.
	 */
	public function set_caching_service( CachingService $service);
}
