<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializationFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\ContainerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\ParentInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\Psr\Container\ContainerInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Initialization extension trait for adding children to oneself from the DI container.
 *
 * @since   1.1.0
 * @version 1.5.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy\Actions
 */
trait AddContainerChildrenTrait {

	// region TRAITS
	use InitializableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * During the initialization process, adds children to one's self from the dependency injection container.
	 *
	 * @since   1.1.0
	 * @version 1.5.0
	 *
	 * @return  InitializationFailureException|null
	 */
	protected function add_container_children() : ?InitializationFailureException {
		$init_result = null;
		if ( $this instanceof ParentInterface ) {
			$di_container = null;
			if ( $this instanceof ContainerAwareInterface ) {
				$di_container = $this->get_container();
			} elseif ( $this instanceof PluginAwareInterface && $this->get_plugin() instanceof ContainerAwareInterface ) {
				/* @noinspection PhpUndefinedMethodInspection */
				$di_container = $this->get_plugin()->get_container();
			}
			if ( $di_container instanceof ContainerInterface ) {
				$children = \array_filter( $this->get_di_container_children(), '\\is_string' );
				foreach ( $children as $child ) {
					$result = $this->add_child( $di_container->get( $child ) );
					if ( $result instanceof InitializationFailureException ) {
						$init_result = $result;
						break;
					}
				}
			}
		}
		return $init_result;
	}
	// endregion
	// region HELPERS
	/**
	 * Using classes should return their DI children here.
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 *
	 * @return  string[]
	 */
	protected function get_di_container_children() : array {
		return array();
	}
	// endregion
}
