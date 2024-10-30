<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy;

\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the child interface.
 *
 * @since   1.0.0
 * @version 1.4.4
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy
 */
trait ChildTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * The parent of the using instance, if it exists.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     ParentInterface|null
	 */
	protected ?ParentInterface $parent = null;
	// endregion
	// region GETTERS
	/**
	 * Returns whether the using instance has a parent or not.
	 *
	 * @since   1.0.0
	 * @version 1.4.4
	 *
	 * @return  bool
	 */
	public function has_parent() : bool {
		return null !== $this->get_parent();
	}
	/**
	 * Returns the parent of the using instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ParentInterface|null
	 */
	public function get_parent() : ?ParentInterface {
		return $this->parent;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets the parent of the using node instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ParentInterface     $parent     The parent of the using instance.
	 */
	public function set_parent( ParentInterface $parent ) {
		$this->parent = $parent;
	}
	// endregion
}
