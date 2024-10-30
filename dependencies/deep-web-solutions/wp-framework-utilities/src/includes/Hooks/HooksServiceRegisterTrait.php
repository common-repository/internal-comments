<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\HooksHelpersTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the hooks service register interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks
 */
trait HooksServiceRegisterTrait {

	// region TRAITS
	use HooksHelpersTrait;
	// endregion
	// region METHODS
	/**
	 * Using classes should define their hooks in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HooksService    $hooks_service      Instance of the hooks service.
	 */
	abstract public function register_hooks( HooksService $hooks_service) : void;
	// endregion
}
