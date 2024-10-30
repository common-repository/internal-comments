<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\Integrations\SetupableInactiveTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotImplementedException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\AdminNoticeTypesEnum;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Helpers\AdminNoticesServiceHelpers;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Helpers\AdminNoticesHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Notices\DismissibleAdminNotice;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Notices\SimpleAdminNotice;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\DependencyContextsEnum;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Handlers\MultiCheckerHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Handlers\SingleCheckerHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Helpers\DependenciesServiceHelpers;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\Helpers\DependenciesHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Dependencies\States\ActiveDependenciesTrait;
use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Trait for registering admin notices of missing dependencies of using instances.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies\Actions
 */
trait SetupActiveStateDependenciesAdminNoticesTrait {

	// region TRAITS
	use ActiveDependenciesTrait;
	use AdminNoticesHelpersTrait;
	use DependenciesHelpersTrait;
	use SetupableExtensionTrait;
	use SetupableInactiveTrait;
	// endregion
	// region METHODS
	/**
	 * Try to automagically register admin notices if dependencies are not fulfilled.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotFoundExceptionInterface      Thrown if the container can't find an entry.
	 * @throws  ContainerExceptionInterface     Thrown if the container encounters some other error.
	 *
	 * @return  SetupFailureException|null
	 */
	public function setup_active_state_dependencies_admin_notices() : ?SetupFailureException {
		$deps_handler = $this->get_dependencies_handler( DependencyContextsEnum::ACTIVE_STATE );
		if ( \is_null( $deps_handler ) ) {
			throw new NotImplementedException( 'Dependencies admin notices scenario not supported' );
		}
		$missing_deps = $deps_handler->get_missing_dependencies();
		if ( $deps_handler instanceof MultiCheckerHandler ) {
			foreach ( $missing_deps as $type => $dependencies ) {
				if ( ! empty( $dependencies ) ) {
					\add_action(
						'init',
						function () use ( $type, $dependencies ) {
							$this->register_missing_dependencies_admin_notices( $dependencies, $type );
						}
					);
				}
			}
		} elseif ( $deps_handler instanceof SingleCheckerHandler ) {
			$type = $deps_handler->get_checker()->get_type();
			\add_action(
				'init',
				function () use ( $type, $missing_deps ) {
					$this->register_missing_dependencies_admin_notices( $missing_deps, $type );
				}
			);
		} else {
			throw new NotImplementedException( 'Dependencies admin notices scenario not supported' );
		}
		return null;
	}
	/**
	 * Register default admin notices for given missing dependencies.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $missing_dependencies   Unfulfilled dependencies.
	 * @param   string  $type                   The type of the unfulfilled dependencies.
	 */
	public function register_missing_dependencies_admin_notices( array $missing_dependencies, string $type ) {
		$notices_service = AdminNoticesServiceHelpers::get_service_from_object( $this );
		foreach ( $missing_dependencies as $checker_id => $dependencies ) {
			if ( empty( $dependencies ) ) {
				continue;
			}
			$is_optional_checker = DependenciesServiceHelpers::is_optional_checker( $checker_id );
			$store_id            = $is_optional_checker ? 'options' : 'dynamic';
			$notice_handle       = $this->get_admin_notice_handle( "missing-{$type}", \md5( \wp_json_encode( $dependencies ) ) );
			$notice_message      = DependenciesServiceHelpers::get_missing_dependencies_notice_message( $type, AdminNoticesServiceHelpers::get_registrant_name( $this ), $dependencies, $is_optional_checker );
			$notice_params       = array( 'capability' => 'activate_plugins' );
			$notice              = $is_optional_checker ? new DismissibleAdminNotice( $notice_handle, $notice_message, AdminNoticeTypesEnum::ERROR, $notice_params + array( 'persistent' => \true ) ) : new SimpleAdminNotice( $notice_handle, $notice_message, AdminNoticeTypesEnum::ERROR, $notice_params );
			$notices_service->add_notice( $notice, $store_id );
		}
	}
	// endregion
}
