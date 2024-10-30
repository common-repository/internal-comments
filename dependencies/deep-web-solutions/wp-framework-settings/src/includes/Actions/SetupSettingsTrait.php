<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\ContainerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsServiceAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsServiceRegisterTrait;
use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for registering settings of using instances.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Actions
 */
trait SetupSettingsTrait {

	// region TRAITS
	use SettingsServiceRegisterTrait;
	use SetupableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * Tries to automagically call the settings service registration method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotFoundExceptionInterface      Thrown if the container can't find an entry.
	 * @throws  ContainerExceptionInterface     Thrown if the container encounters some other error.
	 *
	 * @return  SetupFailureException|null
	 */
	public function setup_settings() : ?SetupFailureException {
		if ( $this instanceof SettingsServiceAwareInterface ) {
			$service = $this->get_settings_service();
		} elseif ( $this instanceof ContainerAwareInterface ) {
			$service = $this->get_container()->get( SettingsService::class );
		} elseif ( $this instanceof PluginAwareInterface && $this->get_plugin() instanceof ContainerAwareInterface ) {
			/* @noinspection PhpUndefinedMethodInspection */
			$service = $this->get_plugin()->get_container()->get( SettingsService::class );
		} else {
			return new SetupFailureException( 'Settings registration setup scenario not supported' );
		}
		$this->register_settings( $service );
		return null;
	}
	// endregion
}
