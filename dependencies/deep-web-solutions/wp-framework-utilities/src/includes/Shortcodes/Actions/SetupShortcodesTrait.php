<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\ContainerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes\ShortcodesService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes\ShortcodesServiceAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Shortcodes\ShortcodesServiceRegisterTrait;
use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for registering shortcodes of using instances.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Shortcodes\Actions
 */
trait SetupShortcodesTrait {

	// region TRAITS
	use ShortcodesServiceRegisterTrait;
	use SetupableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * Try to automagically call the shortcodes registration method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotFoundExceptionInterface      Thrown if the container can't find an entry.
	 * @throws  ContainerExceptionInterface     Thrown if the container encounters some other error.
	 *
	 * @return  SetupFailureException|null
	 */
	public function setup_shortcodes() : ?SetupFailureException {
		if ( $this instanceof ShortcodesServiceAwareInterface ) {
			$service = $this->get_shortcodes_service();
		} elseif ( $this instanceof ContainerAwareInterface ) {
			$service = $this->get_container()->get( ShortcodesService::class );
		} elseif ( $this instanceof PluginAwareInterface && $this->get_plugin() instanceof ContainerAwareInterface ) {
			/* @noinspection PhpUndefinedMethodInspection */
			$service = $this->get_plugin()->get_container()->get( ShortcodesService::class );
		} else {
			return new SetupFailureException( 'Shortcodes registration setup scenario not supported' );
		}
		$this->register_shortcodes( $service );
		return null;
	}
	// endregion
}
