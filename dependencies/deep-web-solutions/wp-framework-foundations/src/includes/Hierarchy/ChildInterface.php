<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance that can have a logical parent.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy
 */
interface ChildInterface {

	// region GETTERS
	/**
	 * Method for determining whether the has a parent or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function has_parent() : bool;
	/**
	 * Method for retrieving the instance's parent.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ParentInterface|null
	 */
	public function get_parent() : ?ParentInterface;
	// endregion
	// region SETTERS
	/**
	 * Method for setting the parent of the instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ParentInterface     $parent     The value to set the parent to.
	 */
	public function set_parent( ParentInterface $parent);
	// endregion
}
