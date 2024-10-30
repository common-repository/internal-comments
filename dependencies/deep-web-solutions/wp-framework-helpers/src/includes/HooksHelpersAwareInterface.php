<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a hooks-helpers-aware instance.
 *
 * @since   1.0.0
 * @version 1.5.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers
 */
interface HooksHelpersAwareInterface {

	/**
	 * Returns a meaningful, hopefully unique, tag for an internal hook.
	 *
	 * @since   1.0.0
	 * @version 1.5.0
	 *
	 * @param   string              $name       The actual descriptor of the hook's purpose.
	 * @param   string|string[]     $extra      Further descriptor of the hook's purpose.
	 * @param   string              $root       Prepended to all hooks inside the same class.
	 *
	 * @return  string
	 */
	public function get_hook_tag( string $name, $extra, string $root) : string;
}
