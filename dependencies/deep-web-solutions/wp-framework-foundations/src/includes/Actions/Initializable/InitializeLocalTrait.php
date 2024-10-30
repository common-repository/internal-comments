<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait for signaling that some local initialization needs to take place too.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Initializable
 */
trait InitializeLocalTrait {

	// region TRAITS
	use InitializableTrait;
	// endregion
	// region METHODS
	/**
	 * Using classes should define their local initialization logic in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  InitializationFailureException|null
	 */
	abstract protected function initialize_local() : ?InitializationFailureException;
	// endregion
}
