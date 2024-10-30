<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance that doesn't do anything before it's explicitly told to. Implementing classes need to define a
 * setup logic.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions
 */
interface SetupableInterface {

	/**
	 * Should be called when the implementing class is ready to start interacting with the environment.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  Setupable\SetupFailureException|null
	 */
	public function setup() : ?Setupable\SetupFailureException;
}
