<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance that can have logical children.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy
 */
interface ParentInterface {

	// region GETTERS
	/**
	 * Method for determining whether the instance has any children or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function has_children() : bool;
	/**
	 * Method for retrieving the instance's children.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ChildInterface[]
	 */
	public function get_children() : array;
	// endregion
	// region SETTERS
	/**
	 * Sets the children on the instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ChildInterface[]        $children       Children to set.
	 */
	public function set_children( array $children);
	// endregion
	// region METHODS
	/**
	 * Method for adding a new child to the instance.
	 *
	 * @param   string|ChildInterface       $child      Object or class name to instantiate and add as a child.
	 */
	public function add_child( $child);
	// endregion
}
