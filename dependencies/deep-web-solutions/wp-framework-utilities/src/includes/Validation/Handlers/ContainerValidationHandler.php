<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation\Handlers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\ContainerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\ContainerAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\InexistentPropertyException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation\AbstractValidationHandler;
use DWS_IC_Deps\Psr\Container\ContainerInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * A validation handler that stores its data in a PSR-11 container.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Validation\Handlers
 */
class ContainerValidationHandler extends AbstractValidationHandler implements ContainerAwareInterface {

	// region TRAITS
	use ContainerAwareTrait;
	// endregion
	// region MAGIC METHODS
	/**
	 * ContainerValidationHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                      $handler_id     The ID of the handler instance.
	 * @param   ContainerInterface|null     $container      PSR-11 container with the validation values.
	 */
	public function __construct( string $handler_id, ?ContainerInterface $container = null ) {
		parent::__construct( $handler_id );
		if ( ! \is_null( $container ) ) {
			$this->set_container( $container );
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
		return $this->get_container_value( 'defaults/' . $key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_known_default_values() : array {
		return \array_keys( $this->get_container_value( 'defaults' ) );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_supported_options( string $key ) {
		return $this->get_container_value( 'options/' . $key );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_known_supported_options() : array {
		return \array_keys( $this->get_container_value( 'options' ) );
	}
	// endregion
	// region HELPERS
	/**
	 * Retrieves a nested value from the container using a composite key.
	 *
	 * @param   string  $key    Composite key of the value to retrieve.
	 *
	 * @return  InexistentPropertyException|mixed
	 */
	protected function get_container_value( string $key ) {
		$boom  = \explode( '/', $key );
		$key   = \array_shift( $boom );
		$value = $this->get_container_entry( $key );
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
