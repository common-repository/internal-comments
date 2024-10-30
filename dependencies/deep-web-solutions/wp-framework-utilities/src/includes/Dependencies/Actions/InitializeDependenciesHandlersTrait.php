<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializationFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\ContainerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\DependenciesHandlerInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\DependenciesService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\DependenciesServiceAwareInterface;
use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for initializing one or more dependencies handlers on the using instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies\Actions
 */
trait InitializeDependenciesHandlersTrait {

	// region TRAITS
	use InitializableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * Try to automagically register one or more dependencies handlers with the dependencies service.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotFoundExceptionInterface      Thrown if the container can't find an entry.
	 * @throws  ContainerExceptionInterface     Thrown if the container encounters some other error.
	 *
	 * @return  InitializationFailureException|null
	 */
	protected function initialize_dependencies_handlers() : ?InitializationFailureException {
		if ( $this instanceof DependenciesServiceAwareInterface ) {
			$service = $this->get_dependencies_service();
		} elseif ( $this instanceof ContainerAwareInterface ) {
			$service = $this->get_container()->get( DependenciesService::class );
		} elseif ( $this instanceof PluginAwareInterface && $this->get_plugin() instanceof ContainerAwareInterface ) {
			/* @noinspection PhpUndefinedMethodInspection */
			$service = $this->get_plugin()->get_container()->get( DependenciesService::class );
		} else {
			return new InitializationFailureException( 'Dependencies handlers initialization scenario not supported' );
		}
		foreach ( $this->get_dependencies_handlers() as $handler ) {
			if ( $handler instanceof DependenciesHandlerInterface ) {
				$service->register_handler( $handler );
			}
		}
		return null;
	}
	/**
	 * Using classes should instantiate one or more dependencies handlers in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  DependenciesHandlerInterface[]
	 */
	abstract protected function get_dependencies_handlers() : array;
	// endregion
}
