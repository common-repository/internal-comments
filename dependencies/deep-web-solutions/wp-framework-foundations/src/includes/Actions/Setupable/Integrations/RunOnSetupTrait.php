<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\Integrations;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupableIntegrationTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupableTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for piping the 'run' method at the end of the setup routine.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Setupable\Integrations
 */
trait RunOnSetupTrait {

	// region TRAITS
	use SetupableIntegrationTrait;
	use SetupableTrait;
	// endregion
	// region METHODS
	/**
	 * After successful setup, call the 'run' method of the using class.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @return  RunFailureException|null
	 */
	protected function integrate_run_on_setup() : ?RunFailureException {
		return $this instanceof RunnableInterface ? $this->run() : null;
	}
	// endregion
}
