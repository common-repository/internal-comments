<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait for signaling that some local setup needs to take place too.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Setupable
 */
trait SetupLocalTrait {

	// region TRAITS
	use SetupableTrait;
	// endregion
	// region METHODS
	/**
	 * Using classes should define their local setup logic in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  SetupFailureException|null
	 */
	abstract protected function setup_local() : ?SetupFailureException;
	// endregion
}
