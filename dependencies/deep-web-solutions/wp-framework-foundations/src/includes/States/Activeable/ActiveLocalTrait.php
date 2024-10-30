<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Activeable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait that classes should use to denote that they want their own is_active logic called.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\States\Activeable
 */
trait ActiveLocalTrait {

	// region TRAITS
	use ActiveableTrait;
	// endregion
	// region METHODS
	/**
	 * Using classes should define their local is_active logic in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	abstract public function is_active_local() : bool;
	// endregion
}
