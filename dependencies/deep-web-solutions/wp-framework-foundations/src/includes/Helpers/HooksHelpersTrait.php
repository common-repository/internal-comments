<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginComponentInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\HooksHelpersTrait as HelpersModuleTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Enhances the helpers' module Hooks trait.
 *
 * @since   1.0.0
 * @version 1.5.1
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Helpers
 */
trait HooksHelpersTrait {

	use HelpersModuleTrait {
		get_hook_tag as protected get_hook_tag_helpers;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.5.1
	 */
	public function get_hook_tag( string $name, $extra = array(), string $root = 'dws_framework_foundations' ) : string {
		if ( $this instanceof PluginComponentInterface ) {
			$root = 'dws_framework_foundations' === $root ? '' : $root;
			$root = \join( '/', array( $this->get_plugin()->get_plugin_safe_slug(), $root ?: $this->get_safe_name() ) );
		} elseif ( 'dws_framework_foundations' === $root ) {
			if ( $this instanceof PluginAwareInterface ) {
				$root = $this->get_plugin()->get_plugin_safe_slug();
			} elseif ( $this instanceof PluginInterface ) {
				$root = $this->get_plugin_safe_slug();
			}
		}
		return $this->get_hook_tag_helpers( $name, $extra, $root );
	}
}
