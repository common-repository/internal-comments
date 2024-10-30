<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait that other traits should use to denote that they want their setup integration logic called
 * after a successful setup.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Setupable
 */
trait SetupableIntegrationTrait {

	// region TRAITS
	use SetupableTrait;
	// endregion
}
