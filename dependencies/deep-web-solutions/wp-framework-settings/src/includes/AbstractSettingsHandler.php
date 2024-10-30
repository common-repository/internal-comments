<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Settings;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\HooksHelpersAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Handles performing actions against a settings framework's API.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
abstract class AbstractSettingsHandler extends AbstractHandler implements SettingsHandlerInterface, HooksHelpersAwareInterface, HooksServiceRegisterInterface, RunnableInterface {

	// region TRAITS
	use HooksServiceRegisterTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * Settings adapter to use for performing the actions.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     SettingsAdapterInterface
	 */
	protected SettingsAdapterInterface $adapter;
	/**
	 * The menu pages registered with the adapter when the handler runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $menu_pages = array();
	/**
	 * The submenu pages registered with the adapter when the handler runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $submenu_pages = array();
	/**
	 * The options groups registered with the adapter when the handler runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $options_groups = array();
	/**
	 * The generic groups registered with the adapter when the handler runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $generic_groups = array();
	/**
	 * The fields registered with the adapter when the handler runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $fields = array();
	/**
	 * Stores whether individual actions have been run already or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool|null[]
	 */
	protected array $is_run = array();
	/**
	 * Stores whether individual actions have been run already or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     RunFailureException|null[]
	 */
	protected array $run_result = array();
	// endregion
	// region MAGIC METHODS
	/**
	 * AbstractSettingsHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                      $handler_id     The ID of the handler instance.
	 * @param   SettingsAdapterInterface    $adapter        Settings adapter to use.
	 */
	public function __construct( string $handler_id, SettingsAdapterInterface $adapter ) {
		parent::__construct( $handler_id );
		$this->adapter = $adapter;
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_adapter() : SettingsAdapterInterface {
		return $this->adapter;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_type() : string {
		return 'settings';
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_hooks( HooksService $hooks_service ) : void {
		foreach ( SettingsActionsEnum::get_all() as $action ) {
			$hook = $this->get_action_hook( $action );
			if ( ! \did_action( $hook ) ) {
				$hooks_service->add_action( $hook, $this, 'run', \PHP_INT_MAX - 100 );
			} else {
				$this->is_run[ $action ]     = \true;
				$this->run_result[ $action ] = \true;
			}
		}
	}
	/**
	 * Registers the pages, groups, and fields using the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  RunFailureException|null
	 */
	public function run() : ?RunFailureException {
		foreach ( SettingsActionsEnum::get_all() as $action ) {
			$hook = $this->get_action_hook( $action );
			if ( \doing_action( $hook ) && \is_null( $this->is_run( $action ) ) ) {
				switch ( $action ) {
					case SettingsActionsEnum::REGISTER_MENU_PAGE:
						\array_walk( $this->menu_pages, array( $this, 'array_walk_register_action' ), SettingsActionsEnum::REGISTER_MENU_PAGE );
						break;
					case SettingsActionsEnum::REGISTER_SUBMENU_PAGE:
						\array_walk( $this->submenu_pages, array( $this, 'array_walk_register_action' ), SettingsActionsEnum::REGISTER_SUBMENU_PAGE );
						break;
					case SettingsActionsEnum::REGISTER_OPTIONS_GROUP:
						\array_walk( $this->options_groups, array( $this, 'array_walk_register_action' ), SettingsActionsEnum::REGISTER_OPTIONS_GROUP );
						break;
					case SettingsActionsEnum::REGISTER_GENERIC_GROUP:
						\array_walk( $this->generic_groups, array( $this, 'array_walk_register_action' ), SettingsActionsEnum::REGISTER_GENERIC_GROUP );
						break;
					case SettingsActionsEnum::REGISTER_FIELD:
						\array_walk( $this->fields, array( $this, 'array_walk_register_action' ), SettingsActionsEnum::REGISTER_FIELD );
						break;
					default:
						continue 2;
				}
				$this->is_run[ $action ]     = \true;
				$this->run_result[ $action ] = null;
			}
		}
		return null;
	}
	// endregion
	// region METHODS
	/**
	 * Returns whether a given settings action has been run already or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string|null     $context    The settings action the query related to.
	 *
	 * @return  bool|null
	 */
	public function is_run( string $context ) : ?bool {
		return $this->is_run[ $context ] ?? null;
	}
	/**
	 * Returns the result of running a given settings action.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string|null     $context    The settings action the query related to.
	 *
	 * @return  RunFailureException|null
	 */
	public function get_run_result( string $context ) : ?RunFailureException {
		return $this->run_result[ $context ] ?? null;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_menu_page( $page_title, $menu_title, string $menu_slug, string $capability, array $params ) {
		if ( $this->is_run( SettingsActionsEnum::REGISTER_MENU_PAGE ) || \did_action( $this->get_action_hook( SettingsActionsEnum::REGISTER_MENU_PAGE ) ) ) {
			return $this->array_walk_register_action( \get_defined_vars(), -1, SettingsActionsEnum::REGISTER_MENU_PAGE );
		} else {
			$this->menu_pages[] = \get_defined_vars();
		}
		return null;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_submenu_page( string $parent_slug, $page_title, $menu_title, string $menu_slug, string $capability, array $params ) {
		if ( $this->is_run( SettingsActionsEnum::REGISTER_SUBMENU_PAGE ) || \did_action( $this->get_action_hook( SettingsActionsEnum::REGISTER_SUBMENU_PAGE ) ) ) {
			return $this->array_walk_register_action( \get_defined_vars(), -1, SettingsActionsEnum::REGISTER_SUBMENU_PAGE );
		} else {
			$this->submenu_pages[] = \get_defined_vars();
		}
		return null;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_options_group( string $group_id, $group_title, $fields, string $page, array $params ) {
		if ( $this->is_run( SettingsActionsEnum::REGISTER_OPTIONS_GROUP ) || \did_action( $this->get_action_hook( SettingsActionsEnum::REGISTER_OPTIONS_GROUP ) ) ) {
			return $this->array_walk_register_action( \get_defined_vars(), -1, SettingsActionsEnum::REGISTER_OPTIONS_GROUP );
		} else {
			$this->options_groups[] = \get_defined_vars();
		}
		return null;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_generic_group( string $group_id, $group_title, $fields, array $locations, array $params ) {
		if ( $this->is_run( SettingsActionsEnum::REGISTER_GENERIC_GROUP ) || \did_action( $this->get_action_hook( SettingsActionsEnum::REGISTER_GENERIC_GROUP ) ) ) {
			return $this->array_walk_register_action( \get_defined_vars(), -1, SettingsActionsEnum::REGISTER_GENERIC_GROUP );
		} else {
			$this->generic_groups[] = \get_defined_vars();
		}
		return null;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_field( string $group_id, string $field_id, $field_title, string $field_type, array $params ) {
		if ( $this->is_run( SettingsActionsEnum::REGISTER_FIELD ) || \did_action( $this->get_action_hook( SettingsActionsEnum::REGISTER_FIELD ) ) ) {
			return $this->array_walk_register_action( \get_defined_vars(), -1, SettingsActionsEnum::REGISTER_FIELD );
		} else {
			$this->fields[] = \get_defined_vars();
		}
		return null;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_option_value( string $field_id, string $settings_id, array $params ) {
		return $this->adapter->get_option_value( $field_id, $settings_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_field_value( string $field_id, $object_id, array $params = array() ) {
		return $this->adapter->get_field_value( $field_id, $object_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function update_option_value( string $field_id, $value, string $settings_id, array $params ) {
		return $this->adapter->update_option_value( $field_id, $value, $settings_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params ) {
		return $this->adapter->update_field_value( $field_id, $value, $object_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_option_value( string $field_id, string $settings_id, array $params ) {
		return $this->adapter->delete_option_value( $field_id, $settings_id, $params );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function delete_field_value( string $field_id, $object_id, array $params ) {
		return $this->adapter->delete_field_value( $field_id, $object_id, $params );
	}
	// endregion
	// region HELPERS
	/**
	 * Calls a method defined on the settings adapter with the given args.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @param   array   $args       Settings action arguments.
	 * @param   int     $key        The key of the entry in the array.
	 * @param   string  $action     Settings action.
	 *
	 * @return  mixed|null
	 */
	protected function array_walk_register_action( array $args, int $key, string $action ) {
		return \call_user_func_array( array( $this->adapter, $action ), \array_values( $args ) );
	}
	// endregion
}
