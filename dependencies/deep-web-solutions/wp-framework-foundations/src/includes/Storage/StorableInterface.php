<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes a storable instance. Storables are objects that can be passed on to stores to perform operations on.
 *
 * @since   1.0.0
 * @version 1.3.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Storage
 */
interface StorableInterface {

	/**
	 * Returns the ID of the instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_id() : string;
}
