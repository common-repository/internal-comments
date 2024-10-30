<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait for signaling that some local run needs to take place too.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Runnable
 */
trait RunLocalTrait {

	// region TRAITS
	use RunnableTrait;
	// endregion
	// region METHODS
	/**
	 * Using classes should define their local run logic in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  RunFailureException|null
	 */
	abstract protected function run_local() : ?RunFailureException;
	// endregion
}
