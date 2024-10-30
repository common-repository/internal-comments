<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage;

\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the storable interface.
 *
 * @since   1.0.0
 * @version 1.3.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Storage
 */
trait StorableTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Type ID of the storable instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     string
	 */
	protected string $storable_id;
	// endregion
	// region GETTERS
	/**
	 * Returns the ID of the instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_id() : string {
		return $this->storable_id;
	}
	// endregion
}
