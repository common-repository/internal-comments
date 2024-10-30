<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a multi-handler-aware-instance.
 *
 * @since   1.0.0
 * @version 1.5.3
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Services
 */
interface MultiHandlerAwareInterface {

	/**
	 * Gets all handler instances set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  HandlerInterface[]
	 */
	public function get_handlers() : array;
	/**
	 * Replaces all handlers set on the object with new ones.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HandlerInterface[]      $handlers       Handler instances to use from now on.
	 */
	public function set_handlers( array $handlers);
	/**
	 * Returns a given handler set on the object by its ID.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler_id     The ID of the handler.
	 *
	 * @return  HandlerInterface|null
	 */
	public function get_handler( string $handler_id) : ?HandlerInterface;
	/**
	 * Registers a new handler with the object.
	 *
	 * @since   1.0.0
	 * @version 1.5.3
	 *
	 * @param   HandlerInterface    $handler    Handler to register with the instance.
	 */
	public function register_handler( HandlerInterface $handler);
}
