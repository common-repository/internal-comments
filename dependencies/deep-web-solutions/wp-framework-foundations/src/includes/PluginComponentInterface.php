<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a plugin component instance. Implementing classes need to define methods that let each instance be uniquely
 * and easily identifiable.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations
 */
interface PluginComponentInterface extends PluginAwareInterface {

	/**
	 * Implementing class should return a hopefully unique ID of the component instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_id() : string;
	/**
	 * Implementing class should return a name of the component instance for potential user-friendliness.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_name() : string;
	/**
	 * Implementing class must ensure this returns a PHP-friendly version of the component instance's name.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_safe_name() : string;
}
