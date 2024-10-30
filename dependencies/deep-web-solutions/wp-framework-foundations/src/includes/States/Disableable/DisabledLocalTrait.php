<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Disableable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait that classes should use to denote that they want their own is_disabled logic called.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\States\Disableable
 */
trait DisabledLocalTrait {

	// region TRAITS
	use DisableableTrait;
	// endregion
	// region METHODS
	/**
	 * Using classes should define their local is_disabled logic in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	abstract public function is_disabled_local() : bool;
	// endregion
}
