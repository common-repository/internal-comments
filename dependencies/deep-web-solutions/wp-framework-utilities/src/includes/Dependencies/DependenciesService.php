<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractMultiHandlerService;
\defined( 'ABSPATH' ) || exit;
/**
 * Queries given handlers for dependencies fulfillment status.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies
 */
class DependenciesService extends AbstractMultiHandlerService {

	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_handler( string $handler_id ) : ?DependenciesHandlerInterface {
        // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_handler( $handler_id );
	}
	// endregion
	// region METHODS
	/**
	 * Returns the dependencies being checked by a given handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $handler_id   The ID of the handler to retrieve the dependencies from.
	 *
	 * @return  array
	 */
	public function get_dependencies( string $handler_id ) : array {
		return $this->get_handler( $handler_id )->get_dependencies();
	}
	/**
	 * Returns the missing dependencies of a given handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $handler_id   The ID of the handler to retrieve the missing dependencies from.
	 *
	 * @return  array
	 */
	public function get_missing_dependencies( string $handler_id ) : array {
		return $this->get_handler( $handler_id )->get_missing_dependencies();
	}
	/**
	 * Returns the dependencies status of a given handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $handler_id   The ID of the handler to retrieve the dependencies status from.
	 *
	 * @return  bool|bool[]|bool[][]
	 */
	public function are_dependencies_fulfilled( string $handler_id ) {
		return $this->get_handler( $handler_id )->are_dependencies_fulfilled();
	}
	// endregion
	// region HELPERS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_handler_class() : string {
		return DependenciesHandlerInterface::class;
	}
	// endregion
}
