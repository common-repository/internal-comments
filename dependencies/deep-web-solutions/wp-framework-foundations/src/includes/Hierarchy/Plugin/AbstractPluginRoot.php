<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\Plugin;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\AbstractPlugin;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\NodeInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\NodeTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\ParentInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating some of the most often required abilities of a tree-like plugin's root.
 *
 * @since   1.0.0
 * @version 1.1.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Hierarchy\Plugin
 */
abstract class AbstractPluginRoot extends AbstractPlugin implements NodeInterface {

	// region TRAITS
	use NodeTrait;
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 */
	final public function get_parent() : ?ParentInterface {
		return null;
	}
	/**
	 * Set the parent to null. Roots have no parent by definition.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 *
	 * @param   ParentInterface|null    $parent     New parent.
	 */
	final public function set_parent( ?ParentInterface $parent = null ) {
		if ( ! \is_null( $parent ) ) {
			$this->log_event( 'The plugin root cannot have a parent', array(), 'framework' )->doing_it_wrong( __FUNCTION__, '1.1.0' )->finalize();
		}
		$this->parent = null;
	}
	// endregion
}
