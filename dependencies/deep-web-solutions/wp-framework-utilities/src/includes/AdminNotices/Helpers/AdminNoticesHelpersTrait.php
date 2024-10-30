<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginComponentInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the admin-notices-helpers-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices\Helpers
 */
trait AdminNoticesHelpersTrait {

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_admin_notice_handle( string $name = '', $extra = array(), string $root = 'dws-framework-utilities' ) : string {
		if ( $this instanceof PluginComponentInterface ) {
			$root = 'dws-framework-utilities' === $root ? '' : $root;
			$root = \join( '_', array( $this->get_plugin()->get_plugin_slug(), $root ?: $this->get_name() ) );
		} elseif ( 'dws-framework-utilities' === $root ) {
			if ( $this instanceof PluginAwareInterface ) {
				$root = $this->get_plugin()->get_plugin_slug();
			} elseif ( $this instanceof PluginInterface ) {
				$root = $this->get_plugin_slug();
			}
		}
		return Strings::to_safe_string(
			\join( '_', \array_filter( \array_merge( array( $root, $name ), Arrays::validate( $extra, array( $extra ) ) ) ) ),
			array(
				' '  => '-',
				'/'  => '-',
				'\\' => '-',
			)
		);
	}
}
