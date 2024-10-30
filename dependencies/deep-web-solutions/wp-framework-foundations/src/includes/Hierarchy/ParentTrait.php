<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy;

\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the parent interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy
 */
trait ParentTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * The children of the using instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     ChildInterface[]
	 */
	protected array $children = array();
	// endregion
	// region GETTERS
	/**
	 * Method for determining whether the instance has any children or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function has_children() : bool {
		return ! empty( $this->get_children() );
	}
	/**
	 * Method for retrieving the instance's children.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ChildInterface[]
	 */
	public function get_children() : array {
		return \array_filter( $this->children, fn( $child) => \is_a( $child, ChildInterface::class ) );
	}
	// endregion
	// region SETTERS
	/**
	 * Sets the children on the instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   object[]    $children   Children to set.
	 */
	public function set_children( array $children ) {
		$this->children = \array_filter( $children, fn( $child) => \is_object( $child ) && \is_a( $child, ChildInterface::class ) );
	}
	// endregion
	// region METHODS
	/**
	 * Method for adding a new child to the instance.
	 *
	 * @param   string|object       $child      Object or class name to instantiate and add as a child.
	 *
	 * @noinspection PhpMissingReturnTypeInspection
	 * @return  bool
	 */
	public function add_child( $child ) {
		if ( ! \is_a( $this, ParentInterface::class ) || ! \is_a( $child, ChildInterface::class, \true ) ) {
			return \false;
		}
		if ( ! \is_object( $child ) ) {
			$child = new $child();
		} elseif ( $child->has_parent() || $child === $this ) {
			return \false;
		}
		$child->set_parent( $this );
		$this->children[] = $child;
		return \true;
	}
	// endregion
}
