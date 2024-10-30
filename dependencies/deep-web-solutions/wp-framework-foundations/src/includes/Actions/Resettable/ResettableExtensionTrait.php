<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait that other traits should use to denote that they want their own reset logic called.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Resettable
 */
trait ResettableExtensionTrait {

	// region TRAITS
	use ResettableTrait;
	// endregion
}
