<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginComponentInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\AssetsHelpersTrait as HelpersModuleTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Enhances the helpers' module Assets trait.
 *
 * @since   1.0.0
 * @version 1.5.1
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Helpers
 */
trait AssetsHelpersTrait {

	use HelpersModuleTrait {
		get_asset_handle as protected get_asset_handle_helpers;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.5.1
	 */
	public function get_asset_handle( string $name = '', $extra = array(), string $root = 'dws-framework-foundations' ) : string {
		if ( $this instanceof PluginComponentInterface ) {
			$root = 'dws-framework-foundations' === $root ? '' : $root;
			$root = \join( '_', array( $this->get_plugin()->get_plugin_slug(), $root ?: $this->get_name() ) );
		} elseif ( 'dws-framework-foundations' === $root ) {
			if ( $this instanceof PluginAwareInterface ) {
				$root = $this->get_plugin()->get_plugin_slug();
			} elseif ( $this instanceof PluginInterface ) {
				$root = $this->get_plugin_slug();
			}
		}
		return $this->get_asset_handle_helpers( $name, $extra, $root );
	}
}
