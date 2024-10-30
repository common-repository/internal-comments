<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance that has a "late constructor". Implementing classes need to define an initialization logic.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions
 */
interface InitializableInterface {

	/**
	 * Should be called when the implementing class is safe to initialize, but before it is actually used.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  Initializable\InitializationFailureException|null
	 */
	public function initialize() : ?Initializable\InitializationFailureException;
}
