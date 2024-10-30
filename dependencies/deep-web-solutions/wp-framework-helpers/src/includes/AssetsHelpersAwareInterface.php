<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an assets-helpers-aware instance.
 *
 * @since   1.0.0
 * @version 1.5.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers
 */
interface AssetsHelpersAwareInterface {

	/**
	 * Returns a meaningful, hopefully unique, handle for an asset.
	 *
	 * @since   1.0.0
	 * @version 1.5.0
	 *
	 * @param   string              $name   The actual descriptor of the asset's purpose. Leave blank for default.
	 * @param   string|string[]     $extra  Further descriptor of the asset's purpose.
	 * @param   string              $root   Prepended to all asset handles inside the same class.
	 *
	 * @return  string
	 */
	public function get_asset_handle( string $name, $extra, string $root) : string;
}
