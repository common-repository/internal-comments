<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection;

use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\ContainerInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the DI-container-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\DependencyInjection
 */
trait ContainerAwareTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Dependency injection container.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     ContainerInterface
	 */
	protected ContainerInterface $di_container;
	// endregion
	// region GETTERS
	/**
	 * Gets an instance of a dependency injection container.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ContainerInterface
	 */
	public function get_container() : ContainerInterface {
		return $this->di_container;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets a container on the instance.
	 *
	 * @param   ContainerInterface      $container      Container instance to use from now on.
	 */
	public function set_container( ContainerInterface $container ) {
		$this->di_container = $container;
	}
	// endregion
	// region HELPERS
	/**
	 * Returns an object from the DI container or null on failure.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $entry_id   The ID of the entry to retrieve from the container.
	 *
	 * @return  mixed|null
	 */
	public function get_container_entry( string $entry_id ) {
		try {
			return $this->get_container()->get( $entry_id );
		} catch ( ContainerExceptionInterface | NotFoundExceptionInterface $exception ) {
			return null;
		}
	}
	// endregion
}
