<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait for signaling that some local output needs to be considered too.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Outputtable
 */
trait OutputLocalTrait {

	// region TRAITS
	use OutputtableTrait;
	// endregion
	// region METHODS
	/**
	 * Using classes should define their local output logic in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  OutputFailureException|null
	 */
	abstract protected function output_local() : ?OutputFailureException;
	// endregion
}
