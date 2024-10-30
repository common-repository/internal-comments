<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\ContainerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotImplementedException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginComponentInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\AdminNoticesService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\AdminNoticesServiceAwareInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * A collection of useful helpers for working with admin notices.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
final class AdminNoticesServiceHelpers {

	/**
	 * Tries to automagically retrieve the admin notice service.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   object  $registrant     The object registering an admin notice.
	 *
	 * @throws  NotImplementedException     Thrown if the admin notice service retrieval scenario is unsupported.
	 *
	 * @return  AdminNoticesService
	 */
	public static function get_service_from_object( object $registrant ) : AdminNoticesService {
		if ( $registrant instanceof AdminNoticesServiceAwareInterface ) {
			$notices_service = $registrant->get_admin_notices_service();
		} elseif ( $registrant instanceof ContainerAwareInterface ) {
			$notices_service = $registrant->get_container()->get( AdminNoticesService::class );
		} elseif ( $registrant instanceof PluginAwareInterface && $registrant->get_plugin() instanceof ContainerAwareInterface ) {
			/* @noinspection PhpUndefinedMethodInspection */
			$notices_service = $registrant->get_plugin()->get_container()->get( AdminNoticesService::class );
		} else {
			throw new NotImplementedException( 'Admin notices service retrieval scenario not supported.' );
		}
		return $notices_service;
	}
	/**
	 * Returns a formatted user-friendly name for the using class.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   object  $registrant     The object registering an admin notice.
	 *
	 * @throws  NotImplementedException     Thrown when called in an unsupported scenario.
	 *
	 * @return  string
	 */
	public static function get_registrant_name( object $registrant ) : string {
		if ( $registrant instanceof PluginInterface ) {
			$name = $registrant->get_plugin_name();
		} elseif ( $registrant instanceof PluginComponentInterface ) {
			$name = \sprintf( '%s: %s', $registrant->get_plugin()->get_plugin_name(), $registrant->get_name() );
		} elseif ( $registrant instanceof PluginAwareInterface ) {
			$name = $registrant->get_plugin()->get_plugin_name();
		} else {
			throw new NotImplementedException( 'Registrant name scenario not supported.' );
		}
		return $name;
	}
}
