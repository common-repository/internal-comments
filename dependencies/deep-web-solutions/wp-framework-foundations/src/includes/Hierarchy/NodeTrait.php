<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy;

use LogicException;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the node interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy
 */
trait NodeTrait {

	// region TRAITS
	use ChildTrait {
		get_parent as protected get_parent_trait;
		set_parent as protected set_parent_child_trait;
	}
	use ParentTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * The depth of the current instance within the tree. The root instance is 0.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     int
	 */
	protected int $depth = 0;
	// endregion
	// region GETTERS
	/**
	 * Returns the parent of the using instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  LogicException  Thrown if the parent is not a node too.
	 *
	 * @return  NodeInterface|null
	 */
	public function get_parent() : ?NodeInterface {
		$parent = $this->get_parent_trait();
		if ( ! \is_null( $parent ) && ! $parent instanceof NodeInterface ) {
			throw new LogicException( 'The parent of a node must be a node too' );
		}
		return $parent;
	}
	/**
	 * Returns the depth of the using instance within the tree.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  int
	 */
	public function get_depth() : int {
		return $this->depth;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets the parent of the using node instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  LogicException  Thrown if the parent is not a node too.
	 *
	 * @param   ParentInterface     $parent     The parent of the using instance.
	 */
	public function set_parent( ParentInterface $parent ) {
		if ( ! $parent instanceof NodeInterface ) {
			throw new LogicException( 'The parent of a node must be a node too' );
		}
		$this->set_parent_child_trait( $parent );
		$this->set_depth( $parent->get_depth() + 1 );
	}
	/**
	 * Sets the depth of the using node instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   int     $depth      The depth of the using distance.
	 */
	public function set_depth( int $depth ) {
		$this->depth = $depth;
	}
	// endregion
}
