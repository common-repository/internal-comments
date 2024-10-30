<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection;

use DWS_IC_Deps\Psr\Container\ContainerInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance aware of multiple DI container.
 *
 * @since   1.5.2
 * @version 1.5.3
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\DependencyInjection
 */
interface MultiContainerAwareInterface {

	/**
	 * Gets all container instances set on the object.
	 *
	 * @since   1.5.2
	 * @version 1.5.2
	 *
	 * @return  ContainerInterface[]
	 */
	public function get_containers() : array;
	/**
	 * Replaces all the containers on the instance with new ones.
	 *
	 * @since   1.5.2
	 * @version 1.5.2
	 *
	 * @param   ContainerInterface[]    $containers     Container instances to use from now on.
	 */
	public function set_containers( array $containers);
	/**
	 * Gets an instance of a dependency injection container.
	 *
	 * @since   1.5.2
	 * @version 1.5.2
	 *
	 * @param   string  $container_id   The ID of the container.
	 *
	 * @return  ContainerInterface|null
	 */
	public function get_container( string $container_id) : ?ContainerInterface;
	/**
	 * Registers a new container with the object.
	 *
	 * @since   1.5.2
	 * @version 1.5.3
	 *
	 * @param   string                  $container_id   The ID of the new container.
	 * @param   ContainerInterface      $container      Container to register with the instance.
	 */
	public function register_container( string $container_id, ContainerInterface $container);
}
