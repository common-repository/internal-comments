<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait for signaling that some local reset needs to take place too.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Resettable
 */
trait ResetLocalTrait {

	// region TRAITS
	use ResettableTrait;
	// endregion
	// region METHODS
	/**
	 * Using classes should define their local reset logic in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ResetFailureException|null
	 */
	abstract protected function reset_local() : ?ResetFailureException;
	// endregion
}
