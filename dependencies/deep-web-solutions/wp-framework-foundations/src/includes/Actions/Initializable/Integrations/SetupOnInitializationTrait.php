<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\Integrations;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableIntegrationTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\SetupableInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for piping the 'setup' method at the end of the initialization routine.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Initializable\Integrations
 */
trait SetupOnInitializationTrait {

	// region TRAITS
	use InitializableIntegrationTrait;
	use InitializableTrait;
	// endregion
	// region METHODS
	/**
	 * After successful initialization, call the 'setup' method of the using class.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @return  SetupFailureException|null
	 */
	protected function integrate_setup_on_initialization() : ?SetupFailureException {
		return $this instanceof SetupableInterface ? $this->setup() : null;
	}
	// endregion
}
