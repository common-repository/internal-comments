<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance that can be logically disabled. Implementing classes need to define the logic of an 'is_disabled'
 * method which determines whether the class is disabled or not.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\States
 */
interface DisableableInterface {

	/**
	 * Should define logic for determining whether the implementing class is disabled or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function is_disabled() : bool;
}
