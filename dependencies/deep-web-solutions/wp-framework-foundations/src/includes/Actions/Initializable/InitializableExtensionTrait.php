<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait that other traits should use to denote that they want their own initialization logic called.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Initializable
 */
trait InitializableExtensionTrait {

	// region TRAITS
	use InitializableTrait;
	// endregion
}
