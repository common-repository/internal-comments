<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a plugin instance. Implementing classes need to define getters pertaining to the WordPress plugin that this
 * framework has been bundled with.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations
 */
interface PluginInterface {

	/**
	 * Implementing class should return the plugin version here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_plugin_name() : string;
	/**
	 * Implementing class should return the plugin version here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_plugin_version() : string;
	/**
	 * Implementing class should return the plugin author's name here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_plugin_author_name() : string;
	/**
	 * Implementing class should return the plugin author's URI here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_plugin_author_uri() : string;
	/**
	 * Implementing class should return the plugin description here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_plugin_description() : string;
	/**
	 * Implementing class should return the plugin's language domain.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_plugin_language_domain() : string;
	/**
	 * Implementing class should return the plugin slug here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_plugin_slug() : string;
	/**
	 * Implementing class must ensure this returns a PHP-friendly version of the plugin slug.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_plugin_safe_slug() : string;
	/**
	 * Implementing class should return the plugin's file path here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_plugin_file_path() : string;
}
