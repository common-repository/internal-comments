<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance that can be logically inactive. Implementing classes need to define the logic of an 'is_active'
 * method which determines whether the class is active or not.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\States
 */
interface ActiveableInterface {

	/**
	 * Should define logic for determining whether the implementing class is active or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function is_active() : bool;
}
