<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage;

\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating some of the most often needed abilities of a storable object.
 *
 * @since   1.0.0
 * @version 1.3.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Storage
 */
abstract class AbstractStorable implements StorableInterface {

	// region TRAITS
	use StorableTrait;
	// endregion
	// region MAGIC METHODS
	/**
	 * AbstractStorable constructor.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   string  $storable_id    The ID of the instance.
	 */
	public function __construct( string $storable_id ) {
		$this->storable_id = $storable_id;
	}
	// endregion
}
