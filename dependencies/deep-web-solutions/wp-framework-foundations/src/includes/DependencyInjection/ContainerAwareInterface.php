<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection;

use DWS_IC_Deps\Psr\Container\ContainerInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance aware of a DI container.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\DependencyInjection
 */
interface ContainerAwareInterface {

	/**
	 * Gets an instance of a dependency injection container.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ContainerInterface
	 */
	public function get_container() : ContainerInterface;
	/**
	 * Sets a container on the instance.
	 *
	 * @param   ContainerInterface      $container      Container instance to use from now on.
	 */
	public function set_container( ContainerInterface $container);
}
