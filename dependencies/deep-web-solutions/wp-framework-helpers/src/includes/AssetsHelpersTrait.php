<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the assets-helpers-aware interface.
 *
 * @since   1.0.0
 * @version 1.7.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers
 */
trait AssetsHelpersTrait {

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.5.0
	 */
	public function get_asset_handle( string $name = '', $extra = array(), string $root = 'dws-framework-helpers' ) : string {
		return Strings::to_safe_string(
			\implode( '_', \array_filter( \array_merge( array( $root, $name ), Arrays::validate( $extra, array( $extra ) ) ) ) ),
			array(
				' '  => '-',
				'/'  => '-',
				'\\' => '-',
			)
		);
	}
}
