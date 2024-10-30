<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractMultiHandlerService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Handlers\WordPressSettingsHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Compatibility layer between the framework and various settings APIs.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
class SettingsService extends AbstractMultiHandlerService implements SettingsServiceInterface, HooksServiceAwareInterface {

	// region TRAITS
	use HooksServiceAwareTrait;
	// endregion
	// region MAGIC METHODS
	/**
	 * SettingsService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   PluginInterface                 $plugin             Instance of the plugin.
	 * @param   LoggingService                  $logging_service    Instance of the logging service.
	 * @param   HooksService                    $hooks_service      Instance of the hooks service.
	 * @param   SettingsHandlerInterface[]      $handlers           Settings handlers to perform actions with.
	 */
	public function __construct( PluginInterface $plugin, LoggingService $logging_service, HooksService $hooks_service, array $handlers = array() ) {
		$this->set_hooks_service( $hooks_service );
		parent::__construct( $plugin, $logging_service, $handlers );
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_handler( string $handler_id ) : ?SettingsHandlerInterface {
        // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_handler( $handler_id );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_handler( HandlerInterface $handler ) : SettingsService {
		parent::register_handler( $handler );
		if ( $handler instanceof HooksServiceRegisterInterface ) {
			$handler->register_hooks( $this->get_hooks_service() );
		}
		return $this;
	}
	// endregion
	// region METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_menu_page( $page_title, $menu_title, string $menu_slug, string $capability, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->register_menu_page( $page_title, $menu_title, $menu_slug, $capability, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_submenu_page( string $parent_slug, $page_title, $menu_title, string $menu_slug, string $capability, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->register_submenu_page( $parent_slug, $page_title, $menu_title, $menu_slug, $capability, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_options_group( string $group_id, $group_title, $fields, string $page, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->register_options_group( $group_id, $group_title, $fields, $page, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_generic_group( string $group_id, $group_title, $fields, array $locations, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->register_generic_group( $group_id, $group_title, $fields, $locations, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_field( string $group_id, string $field_id, $field_title, string $field_type, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->register_field( $group_id, $field_id, $field_title, $field_type, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_option_value( ?string $field_id, string $settings_id, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->get_option_value( $field_id, $settings_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_field_value( string $field_id, $object_id, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->get_field_value( $field_id, $object_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function update_option_value( ?string $field_id, $value, string $settings_id, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->update_option_value( $field_id, $value, $settings_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->update_field_value( $field_id, $value, $object_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_option_value( ?string $field_id, string $settings_id, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->delete_option_value( $field_id, $settings_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_field_value( string $field_id, $object_id, array $params = array(), string $handler_id = 'WordPress' ) {
        // phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
		return $this->get_handler( $handler_id )->delete_field_value( $field_id, $object_id, $params );
	}
	// endregion
	// region HELPERS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_default_handlers_classes() : array {
		return array( WordPressSettingsHandler::class );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_handler_class() : string {
		return SettingsHandlerInterface::class;
	}
	// endregion
}
