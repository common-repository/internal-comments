<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\States;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotImplementedException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Disableable\DisableableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\DisableableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\DependencyContextsEnum;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Helpers\DependenciesServiceHelpers;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Helpers\DependenciesHelpersTrait;
use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Abstract trait for dependent disablement of instances with dependencies.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies\States
 */
trait DisabledDependenciesTrait {

	// region TRAITS
	use DependenciesHelpersTrait;
	use DisableableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * If the using class is disableable, disable it if required dependencies are not fulfilled.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotFoundExceptionInterface      Thrown if the container can't find an entry.
	 * @throws  ContainerExceptionInterface     Thrown if the container encounters any other error.
	 * @throws  NotImplementedException         Thrown when using this function in an unsupported context.
	 *
	 * @return  bool
	 */
	public function is_disabled_dependencies() : bool {
		$is_disabled = \false;
		if ( $this instanceof DisableableInterface ) {
			$handler = $this->get_dependencies_handler( DependencyContextsEnum::DISABLED_STATE );
			if ( \is_null( $handler ) ) {
				throw new NotImplementedException( 'Dependency checking scenario not supported' );
			}
			$are_deps_fulfilled = $handler->are_dependencies_fulfilled();
			$is_disabled        = ! DependenciesServiceHelpers::status_to_boolean( $are_deps_fulfilled, \false );
		}
		return $is_disabled;
	}
	// endregion
}
