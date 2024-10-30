<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\MultiContainerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\MultiContainerAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\InexistentPropertyException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotSupportedException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation\AbstractValidationHandler;
use DWS_IC_Deps\Psr\Container\ContainerInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * A validation handler that stores its data in two PSR-11 containers.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Validation\Handlers
 */
class MultiContainerValidationHandler extends AbstractValidationHandler implements MultiContainerAwareInterface {

	// region TRAITS
	use MultiContainerAwareTrait;
	// endregion
	// region MAGIC METHODS
	/**
	 * MultiContainerValidationHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                      $handler_id             The ID of the handler instance.
	 * @param   ContainerInterface|null     $defaults_container     PSR-11 container with the validation defaults.
	 * @param   ContainerInterface|null     $options_container      PSR-11 container with the validation options.
	 */
	public function __construct( string $handler_id, ?ContainerInterface $defaults_container = null, ?ContainerInterface $options_container = null ) {
		parent::__construct( $handler_id );
		if ( ! \is_null( $defaults_container ) ) {
			$this->register_container( 'defaults', $defaults_container );
		}
		if ( ! \is_null( $options_container ) ) {
			$this->register_container( 'options', $options_container );
		}
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_default_value( string $key ) {
		return $this->get_container_value( $key, 'defaults' );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotSupportedException   This method is not supported when using multiple containers.
	 */
	public function get_known_default_values() : array {
		throw new NotSupportedException();
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_supported_options( string $key ) {
		return $this->get_container_value( $key, 'options' );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotSupportedException   This method is not supported when using multiple containers.
	 */
	public function get_known_supported_options() : array {
		throw new NotSupportedException();
	}
	// endregion
	// region HELPERS
	/**
	 * Retrieves a nested value from the container using a composite key.
	 *
	 * @param   string  $key            Composite key of the value to retrieve.
	 * @param   string  $container_id   The ID of the container to retrieve the value from.
	 *
	 * @return  InexistentPropertyException|mixed
	 */
	protected function get_container_value( string $key, string $container_id ) {
		$boom  = \explode( '/', $key );
		$key   = \array_shift( $boom );
		$value = $this->get_container_entry( $key, $container_id );
		if ( \is_null( $value ) ) {
			return new InexistentPropertyException( \sprintf( 'Inexistent container entry: %s', $key ) );
		}
		foreach ( $boom as $key ) {
			if ( isset( $value[ $key ] ) || \array_key_exists( $key, $value ) ) {
				// This will support entries containing literal NULL.
				$value = $value[ $key ];
			} else {
				return new InexistentPropertyException( \sprintf( 'Inexistent container entry: %s', $key ) );
			}
		}
		return $value;
	}
	// endregion
}
