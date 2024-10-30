<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Helpers;

use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Booleans;
\defined( 'ABSPATH' ) || exit;
/**
 * A collection of very useful WP users helpers to be used throughout the projects.
 *
 * @since   1.0.0
 * @version 1.7.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers
 */
final class Users {

	/**
	 * Attempts to retrieve a WP_User instance by a user's ID.
	 *
	 * @since   1.4.0
	 * @version 1.5.5
	 *
	 * @param   int|null    $user_id    The ID of the user to retrieve. Defaults to the currently logged-in user or the guest user.
	 *
	 * @return  \WP_User|null
	 */
	public static function get( ?int $user_id = null ) : ?\WP_User {
		if ( \is_null( $user_id ) ) {
			$user = \wp_get_current_user();
		} elseif ( 0 === $user_id ) {
			$user = new \WP_User( $user_id );
		} else {
			$user = \get_user_by( 'id', $user_id );
		}
		return $user instanceof \WP_User ? $user : null;
	}
	/**
	 * Retrieves the list of roles that a given user has. Defaults to the current logged-in user.
	 *
	 * @since   1.0.0
	 * @version 1.4.0
	 *
	 * @param   int|null    $user_id    The ID of the user to retrieve the roles for. Defaults to the currently logged-in user.
	 *
	 * @return  array|null
	 */
	public static function get_roles( ?int $user_id = null ) : ?array {
		$user = self::get( $user_id );
		return \is_null( $user ) ? null : $user->roles;
	}
	/**
	 * Checks whether a given user has certain roles.
	 *
	 * @since   1.0.0
	 * @version 1.4.6
	 *
	 * @param   string[]|string     $roles      The roles to check for.
	 * @param   int|null            $user_id    The ID of the user to check for. Defaults to the currently logged-in user.
	 * @param   string              $logic      Whether the user needs to have all the roles mentioned or either of them. Valid values: 'and', 'or'. Default: 'or'.
	 *
	 * @return  bool|null
	 */
	public static function has_roles( $roles, ?int $user_id = null, string $logic = 'or' ) : ?bool {
		$roles = \is_array( $roles ) ? $roles : array( $roles );
		if ( empty( $roles ) ) {
			return \true;
		}
		$user_roles = self::get_roles( $user_id );
		if ( \is_null( $user_roles ) ) {
			return null;
		}
		if ( empty( $user_roles ) ) {
			return \false;
		}
		return \array_reduce( \array_map( static fn( string $role) => \in_array( $role, $user_roles, \true ), $roles ), array( Booleans::class, "logical_{$logic}" ), 'and' === $logic );
	}
	/**
	 * Checks whether a given user has certain capabilities.
	 *
	 * @since   1.0.0
	 * @version 1.4.6
	 *
	 * @param   string[]|string     $capabilities   The capabilities to check for.
	 * @param   array               $args           Optional further parameters, typically starting with an object ID. See @user_can.
	 * @param   int|null            $user_id        The ID of the user to check for. Defaults to the currently logged-in user.
	 * @param   string              $logic          Whether the user needs to have all the roles mentioned or either of them. Valid values: 'and', 'or'. Default: 'and'.
	 *
	 * @return  bool|null
	 */
	public static function has_capabilities( $capabilities, array $args = array(), ?int $user_id = null, string $logic = 'and' ) : ?bool {
		$capabilities = \is_array( $capabilities ) ? $capabilities : array( $capabilities );
		if ( empty( $capabilities ) ) {
			return \true;
		}
		$user = self::get( $user_id );
		if ( \is_null( $user ) ) {
			return null;
		}
		return \array_reduce( \array_map( static fn( string $capability) => $user->has_cap( $capability, ...$args ), $capabilities ), array( Booleans::class, "logical_{$logic}" ), 'and' === $logic );
	}
	/**
	 * Logs out a given user from WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.7.0
	 *
	 * @param   int|null    $user_id    The ID of the user to log out. Defaults to the currently logged-in user.
	 */
	public static function logout( ?int $user_id = null ) : void {
		$user_id = $user_id ?? \get_current_user_id();
		if ( \get_current_user_id() === $user_id ) {
			\wp_destroy_all_sessions();
			\wp_logout();
		} else {
			$user_sessions = \WP_Session_Tokens::get_instance( $user_id );
			$user_sessions->destroy_all();
		}
	}
}
