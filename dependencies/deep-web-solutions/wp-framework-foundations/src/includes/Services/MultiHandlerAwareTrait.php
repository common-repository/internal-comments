<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\MultiStoreAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StoreInterface;
use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the multi-handler-aware interface.
 *
 * @see     MultiHandlerAwareInterface
 * @since   1.0.0
 * @version 1.5.3
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Services
 */
trait MultiHandlerAwareTrait {

	// region TRAITS
	use MultiStoreAwareTrait;
	// endregion
	// region GETTERS
	/**
	 * Gets the handlers store instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  StoreInterface|null
	 */
	public function get_handlers_store() : ?StoreInterface {
		return $this->get_store( 'handlers' );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_handlers() : array {
		try {
			return $this->get_handlers_store()->get_all();
		} catch ( ContainerExceptionInterface $exception ) {
			return array();
		}
	}
	// endregion
	// region SETTERS
	/**
	 * Sets the handlers store on the instance.
	 *
	 * @since   1.0.0
	 * @version 1.5.3
	 *
	 * @param   StoreInterface      $store      Handlers store to set.
	 *
	 * @throws  \LogicException     Thrown if the handlers store has the wrong ID.
	 */
	public function set_handlers_store( StoreInterface $store ) {
		if ( 'handlers' !== $store->get_id() ) {
			throw new \LogicException( 'The handlers store must have the ID "handlers"' );
		}
		$this->register_store( $store );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function set_handlers( array $handlers ) : self {
		$this->get_handlers_store()->empty();
		foreach ( $handlers as $handler ) {
			if ( $handler instanceof HandlerInterface ) {
				$this->register_handler( $handler );
			}
		}
		return $this;
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_handler( string $handler_id ) : ?HandlerInterface {
		try {
			return $this->get_handlers_store()->get( $handler_id );
		} catch ( ContainerExceptionInterface | NotFoundExceptionInterface $exception ) {
			return null;
		}
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.5.3
	 */
	public function register_handler( HandlerInterface $handler ) {
		$this->get_handlers_store()->update( $handler );
		return $this;
	}
	// endregion
}
