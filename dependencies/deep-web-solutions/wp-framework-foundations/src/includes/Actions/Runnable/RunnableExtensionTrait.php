<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable;

\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait that other traits should use to denote that they want their own run logic called.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Runnable
 */
trait RunnableExtensionTrait {

	// region TRAITS
	use RunnableTrait;
	// endregion
}
