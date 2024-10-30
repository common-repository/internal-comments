<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Objects;
\defined( 'ABSPATH' ) || exit;
/**
 * Provides some useful helpers for working with local extension traits.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Helpers
 */
trait ActionLocalExtensionHelpersTrait {

	/**
	 * Execute the local extension trait action logic.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $local_trait        The name of the extension trait to look for.
	 * @param   string  $method_prefix      The prefix of the local action method.
	 * @param   mixed   $success_return     Default return value on success.
	 *
	 * @return  mixed
	 */
	protected function maybe_execute_local_trait( string $local_trait, string $method_prefix, $success_return = null ) {
		if ( Objects::has_trait_deep( $local_trait, $this ) && \method_exists( $this, $method_name = "{$method_prefix}_local" ) ) {
            // phpcs:ignore
            return $this->{$method_name}();
		}
		return $success_return;
	}
}
