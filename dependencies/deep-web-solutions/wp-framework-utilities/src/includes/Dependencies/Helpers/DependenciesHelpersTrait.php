<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\ContainerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginComponentInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\DependenciesHandlerInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\DependenciesService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\DependenciesServiceAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\DependencyContextsEnum;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the dependencies-helpers-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies\Helpers
 */
trait DependenciesHelpersTrait {

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_dependencies_handler_id( ?string $context = null ) : string {
		switch ( $context ) {
			case DependencyContextsEnum::ACTIVE_STATE:
				$handler_id = 'active_%s';
				break;
			case DependencyContextsEnum::DISABLED_STATE:
				$handler_id = 'disabled_%s';
				break;
			default:
				$handler_id = '%s';
		}
		if ( $this instanceof PluginComponentInterface ) {
			$handler_id = \sprintf( $handler_id, $this->get_id() );
		} elseif ( $this instanceof PluginInterface ) {
			$handler_id = \sprintf( $handler_id, $this->get_plugin_slug() );
		} else {
			$handler_id = \sprintf( $handler_id, \get_class( $this ) );
		}
		return $handler_id;
	}
	/**
	 * Tries to automagically return an instance of a dependencies handler registered with the dependencies service.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string|null     $context    The context of the dependencies handler.
	 *
	 * @return  DependenciesHandlerInterface|null
	 */
	public function get_dependencies_handler( ?string $context = null ) : ?DependenciesHandlerInterface {
		$handler_id = $this->get_dependencies_handler_id( $context );
		$handler    = null;
		if ( $this instanceof DependenciesServiceAwareInterface ) {
			$handler = $this->get_dependencies_service()->get_handler( $handler_id );
		} elseif ( $this instanceof ContainerAwareInterface ) {
			$handler = $this->get_container()->get( DependenciesService::class )->get_handler( $handler_id );
		} elseif ( $this instanceof PluginAwareInterface && $this->get_plugin() instanceof ContainerAwareInterface ) {
			/* @noinspection PhpUndefinedMethodInspection */
			$handler = $this->get_plugin()->get_container()->get( DependenciesService::class )->get_handler( $handler_id );
		}
		return $handler;
	}
}
