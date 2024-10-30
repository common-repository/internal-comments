<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Activeable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait that other traits should use to denote that they want their own is_active logic called.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\States\Activeable
 */
trait ActiveableExtensionTrait {

	// region TRAITS
	use ActiveableTrait;
	// endregion
}
