<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait that other traits should use to denote that they want their own setup logic called.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Setupable
 */
trait SetupableExtensionTrait {

	// region TRAITS
	use SetupableTrait;
	// endregion
}
