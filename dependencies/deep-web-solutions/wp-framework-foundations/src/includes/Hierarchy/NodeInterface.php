<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance aware of both children and parents. Implementing classes need to define a logic for
 * handling parent-child relations.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy
 */
interface NodeInterface extends ParentInterface, ChildInterface {

	// region GETTERS
	/**
	 * Method for retrieving the node's depth within the tree.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  int
	 */
	public function get_depth() : int;
	// endregion
	// region SETTERS
	/**
	 * Method for setting the depth of the node.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   int     $depth      The value to set the depth to.
	 *
	 * @return  mixed
	 */
	public function set_depth( int $depth);
	// endregion
}
