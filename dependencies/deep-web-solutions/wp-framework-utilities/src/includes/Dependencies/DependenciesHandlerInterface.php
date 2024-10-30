<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance of a shortcodes handler compatible with the shortcodes service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies
 */
interface DependenciesHandlerInterface extends HandlerInterface {

	/**
	 * Returns the dependencies checked for.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	public function get_dependencies() : array;
	/**
	 * Returns the unfulfilled dependencies.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	public function get_missing_dependencies() : array;
	/**
	 * Returns whether the dependencies are fulfilled or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool|bool[]|bool[][]
	 */
	public function are_dependencies_fulfilled();
}
