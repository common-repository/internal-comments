<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Helpers;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an admin-notices-helpers-aware object.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices\Helpers
 */
interface AdminNoticesHelpersAwareInterface {

	/**
	 * Returns a meaningful, hopefully unique, handle for an admin notice.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $name   The actual descriptor of the notice's purpose.
	 * @param   string|string[]     $extra  Further descriptor of the notice's purpose.
	 * @param   string              $root   Prepended to all notice handles inside the same class.
	 *
	 * @return  string
	 */
	public function get_admin_notice_handle( string $name, $extra, string $root) : string;
}
