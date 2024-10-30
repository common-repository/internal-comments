<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\Integrations;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableIntegrationTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\SetupableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\ActiveableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\DisableableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Objects;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for piping the 'setup' method at the end of the initialization routine conditionally based on the instance's state.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions\Initializable\Integrations
 */
trait MaybeSetupOnInitializationTrait {

	// region TRAITS
	use InitializableIntegrationTrait;
	use InitializableTrait;
	// endregion
	// region METHODS
	/**
	 * After successful initialization, call the 'setup' method of the using class if appropriate.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @return  SetupFailureException|null
	 */
	protected function integrate_maybe_setup_on_initialization() : ?SetupFailureException {
		return $this instanceof SetupableInterface && $this->should_setup() ? $this->setup() : null;
	}
	/**
	 * Decide whether the instance should setup or not based on its state.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @return  bool
	 */
	protected function should_setup() : bool {
		$should_setup = \true;
		if ( $this instanceof DisableableInterface ) {
			$should_setup = ! $this->is_disabled() || Objects::has_trait_deep( SetupableDisabledTrait::class, $this );
		}
		if ( $should_setup && $this instanceof ActiveableInterface ) {
			$should_setup = $this->is_active() || Objects::has_trait_deep( SetupableInactiveTrait::class, $this );
		}
		return $should_setup;
	}
	// endregion
}
