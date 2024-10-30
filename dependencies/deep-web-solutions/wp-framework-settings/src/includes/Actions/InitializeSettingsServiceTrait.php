<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializationFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\ContainerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\ChildInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsServiceAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsServiceAwareTrait;
use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for setting the settings service on the using instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Actions
 */
trait InitializeSettingsServiceTrait {

	// region TRAITS
	use SettingsServiceAwareTrait;
	use InitializableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * Try to automagically set a settings service on the instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotFoundExceptionInterface      Thrown if the container can't find an entry.
	 * @throws  ContainerExceptionInterface     Thrown if the container encounters some other error.
	 *
	 * @return  InitializationFailureException|null
	 */
	public function initialize_settings_service() : ?InitializationFailureException {
		if ( $this instanceof ChildInterface && $this->get_parent() instanceof SettingsServiceAwareInterface ) {
			/* @noinspection PhpUndefinedMethodInspection */
			$service = $this->get_parent()->get_settings_service();
		} elseif ( $this instanceof ContainerAwareInterface ) {
			$service = $this->get_container()->get( SettingsService::class );
		} elseif ( $this instanceof PluginAwareInterface && $this->get_plugin() instanceof ContainerAwareInterface ) {
			/* @noinspection PhpUndefinedMethodInspection */
			$service = $this->get_plugin()->get_container()->get( SettingsService::class );
		} else {
			return new InitializationFailureException( 'Settings service initialization scenario not supported' );
		}
		$this->set_settings_service( $service );
		return null;
	}
	// endregion
}
