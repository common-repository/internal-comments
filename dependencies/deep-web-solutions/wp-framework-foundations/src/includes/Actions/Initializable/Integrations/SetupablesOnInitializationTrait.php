<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\Integrations;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableIntegrationTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\SetupableInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for piping the 'setup' method of setupable objects at the end of the initialization routine.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Initializable\Integrations
 */
trait SetupablesOnInitializationTrait {

	// region TRAITS
	use InitializableIntegrationTrait;
	use InitializableTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * List of setupable objects to setup on successful initialization.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @access  protected
	 * @var     SetupableInterface[]
	 */
	protected array $setupables_on_initialize = array();
	// endregion
	// region METHODS
	/**
	 * After successful initialization, call the 'setup' method of all registered setupable objects.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @return  SetupFailureException|null
	 */
	protected function integrate_setupables_on_initialization() : ?SetupFailureException {
		foreach ( $this->setupables_on_initialize as $setupable ) {
			$result = $setupable->setup();
			if ( ! \is_null( $result ) ) {
				return $result;
			}
		}
		return null;
	}
	/**
	 * Adds an object to the list of setupable objects to setup on successful initialization.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @param   SetupableInterface      $setupable      Setupable object to register with the class instance.
	 *
	 * @return  self
	 */
	public function register_setupable_on_initialization( SetupableInterface $setupable ) : self {
		$this->setupables_on_initialize[] = $setupable;
		return $this;
	}
	// endregion
}
