<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations;

\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the plugin-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations
 */
trait PluginAwareTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * The plugin instance that the using class belongs to.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     PluginInterface
	 */
	protected PluginInterface $plugin;
	// endregion
	// region GETTERS
	/**
	 * Returns the plugin instance that the using class instance belongs to.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  PluginInterface
	 */
	public function get_plugin() : PluginInterface {
		return $this->plugin;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets the plugin instance that the using class instance belongs to.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   PluginInterface     $plugin     The instance of the plugin to use from now on.
	 */
	public function set_plugin( PluginInterface $plugin ) {
		$this->plugin = $plugin;
	}
	// endregion
}
