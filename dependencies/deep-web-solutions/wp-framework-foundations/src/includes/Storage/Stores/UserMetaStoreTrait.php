<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\Stores;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotFoundException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StorableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StoreException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of a user-meta store.
 *
 * @since   1.0.0
 * @version 1.5.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Storage\Stores
 */
trait UserMetaStoreTrait {

	// region METHODS
	/**
	 * Returns the identifier of the store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	abstract public function get_id() : string;
	/**
	 * Returns the key used to store the objects in the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	abstract public function get_key() : string;
	/**
	 * Returns the storage medium of the store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_storage_type() : string {
		return 'user-meta';
	}
	/**
	 * Returns the total number of entries stored for a given user.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   int|null    $user_id    The ID of the user to count the stored objects for.
	 *
	 * @return  int
	 */
	public function count( ?int $user_id = null ) : int {
		$user_id = $this->parse_user_id( $user_id );
		return \count( $this->get_all( $user_id ) );
	}
	/**
	 * Checks whether an entry currently exists or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $entry_id       The identifier of the entry.
	 * @param   int|null    $user_id        The ID of the user to check the stored objects for.
	 *
	 * @return  bool
	 */
	public function has( string $entry_id, ?int $user_id = null ) : bool {
		$entry_id = $this->sanitize_entry_id( $entry_id );
		$user_id  = $this->parse_user_id( $user_id );
		return isset( $this->get_all( $user_id )[ $entry_id ] );
	}
	// endregion
	// region CRUD
	/**
	 * Returns all the entries stored.
	 *
	 * @since   1.0.0
	 * @version 1.5.0
	 *
	 * @param   int|null    $user_id        The ID of the user to retrieve the stored objects for.
	 *
	 * @return  StorableInterface[]
	 */
	public function get_all( ?int $user_id = null ) : array {
		$user_id = $this->parse_user_id( $user_id );
		return Arrays::validate( \get_user_meta( $user_id, $this->get_key(), \true ), array() );
	}
	/**
	 * Returns an entry from the store.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   string      $entry_id       The identifier of the entry.
	 * @param   int|null    $user_id        The ID of the user to retrieve the stored object for.
	 *
	 * @throws  NotFoundException   Thrown when the entry does not exist.
	 *
	 * @return  StorableInterface
	 */
	public function get( string $entry_id, ?int $user_id = null ) : StorableInterface {
		$user_id = $this->parse_user_id( $user_id );
		if ( $this->has( $entry_id, $user_id ) ) {
			$entry_id = $this->sanitize_entry_id( $entry_id );
			return $this->get_all( $user_id )[ $entry_id ];
		}
		throw new NotFoundException( \sprintf( 'Could not retrieve entry %1$s from user %2$s. Not found in store %3$s of type %4$s', $entry_id, $user_id, $this->get_id(), $this->get_storage_type() ) );
	}
	/**
	 * Adds an entry to the store.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   StorableInterface   $storable   Object to store.
	 * @param   int|null            $user_id    The ID of the user to add the object to.
	 *
	 * @throws  StoreException      Error while adding the entry.
	 */
	public function add( StorableInterface $storable, ?int $user_id = null ) {
		$entry_id = $storable->get_id();
		$user_id  = $this->parse_user_id( $user_id );
		if ( $this->has( $entry_id, $user_id ) ) {
			throw new StoreException( \sprintf( 'Entry %1$s already exists in store %2$s of type %3$s for user %4$s', $entry_id, $this->get_id(), $this->get_storage_type(), $user_id ) );
		}
		$this->update( $storable, $user_id );
	}
	/**
	 * Updates (or adds if it doesn't exist) an entry in the store.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   StorableInterface   $storable   Object to add or update.
	 * @param   int|null            $user_id    The ID of the user to add or update the object to.
	 *
	 * @return  bool
	 */
	public function update( StorableInterface $storable, ?int $user_id = null ) : bool {
		$entry_id = $this->sanitize_entry_id( $storable->get_id() );
		$user_id  = $this->parse_user_id( $user_id );
		return \update_user_meta( $user_id, $this->get_key(), \array_merge( $this->get_all(), array( $entry_id => $storable ) ) );
	}
	/**
	 * Removes an entry from the store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $entry_id       The identifier of the entry.
	 * @param   int|null    $user_id        The ID of the user to remove the stored object from.
	 *
	 * @throws  NotFoundException   Thrown when the entry does not exist.
	 *
	 * @return  bool
	 */
	public function remove( string $entry_id, ?int $user_id = null ) : bool {
		$user_id = $this->parse_user_id( $user_id );
		if ( $this->has( $entry_id, $user_id ) ) {
			$stored_objects = $this->get_all( $user_id );
			$entry_id       = $this->sanitize_entry_id( $entry_id );
			unset( $stored_objects[ $entry_id ] );
			return empty( $stored_objects ) ? $this->empty( $user_id ) : \update_user_meta( $user_id, $this->get_key(), $stored_objects );
		}
		throw new NotFoundException( \sprintf( 'Could not delete entry %1$s from user %2$s. Not found in store %3$s of type %4$s', $entry_id, $user_id, $this->get_id(), $this->get_storage_type() ) );
	}
	/**
	 * Removes all objects from the store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   int|null    $user_id        The ID of the user to remove the stored object from.
	 */
	public function empty( ?int $user_id = null ) : bool {
		$user_id = $this->parse_user_id( $user_id );
		return \delete_user_meta( $user_id, $this->get_key() );
	}
	// endregion
	// region HELPERS
	/**
	 * Ensures the user ID is set.
	 *
	 * @param   int|null    $user_id    The ID to parse.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  int
	 */
	protected function parse_user_id( ?int $user_id ) : int {
		return $user_id ?? \get_current_user_id();
	}
	/**
	 * Ensures that the entry ID is safe to save into the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $entry_id   The string to sanitize.
	 *
	 * @return  string
	 */
	protected function sanitize_entry_id( string $entry_id ) : string {
		return \sanitize_key( $entry_id );
	}
	// endregion
}
