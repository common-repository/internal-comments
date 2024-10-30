<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\Integrations;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupableIntegrationTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupableTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for piping the 'run' method of given runnable objects at the end of the setup routine.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Setupable\Integrations
 */
trait RunnablesOnSetupTrait {

	// region TRAITS
	use SetupableIntegrationTrait;
	use SetupableTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * List of runnable objects to run on successful setup.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @access  protected
	 * @var     RunnableInterface[]
	 */
	protected array $runnables_on_setup = array();
	// endregion
	// region METHODS
	/**
	 * After successful setup, call the 'run' method of all registered runnable objects.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @return  RunFailureException|null
	 */
	protected function integrate_runnables_on_setup() : ?RunFailureException {
		foreach ( $this->runnables_on_setup as $runnable ) {
			$result = $runnable->run();
			if ( ! \is_null( $result ) ) {
				return $result;
			}
		}
		return null;
	}
	/**
	 * Adds an object to the list of runnable objects to run on successful setup.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @param   RunnableInterface       $runnable       Runnable object to register with the class instance.
	 *
	 * @return  self
	 */
	public function register_runnable_on_setup( RunnableInterface $runnable ) : self {
		$this->runnables_on_setup[] = $runnable;
		return $this;
	}
	// endregion
}
