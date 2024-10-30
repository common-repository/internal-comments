<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\Stores;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Exceptions\NotFoundException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StorableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StoreException;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of an options table store.
 *
 * @since   1.0.0
 * @version 1.3.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Storage\Stores
 */
trait OptionsStoreTrait {

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
		return 'options-table';
	}
	/**
	 * Returns the total number of entries stored.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  int
	 */
	public function count() : int {
		return \count( $this->get_all() );
	}
	/**
	 * Checks whether an entry currently exists or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $entry_id     The identifier of the entry.
	 *
	 * @return  bool
	 */
	public function has( string $entry_id ) : bool {
		$entry_id = $this->sanitize_entry_id( $entry_id );
		return isset( $this->get_all()[ $entry_id ] );
	}
	// endregion
	// region CRUD
	/**
	 * Returns all the entries stored.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @return  StorableInterface[]
	 */
	public function get_all() : array {
		return \get_option( $this->get_key(), array() );
	}
	/**
	 * Returns an entry from the store.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   string  $entry_id   The identifier of the entry.
	 *
	 * @throws  NotFoundException   Thrown when the entry does not exist.
	 *
	 * @return  StorableInterface
	 */
	public function get( string $entry_id ) : StorableInterface {
		if ( $this->has( $entry_id ) ) {
			$entry_id = $this->sanitize_entry_id( $entry_id );
			return $this->get_all()[ $entry_id ];
		}
		throw new NotFoundException( \sprintf( 'Could not retrieve entry %1$s. Not found in store %2$s of type %3$s', $entry_id, $this->get_id(), $this->get_storage_type() ) );
	}
	/**
	 * Adds an entry to the store.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   StorableInterface   $storable   Object to store.
	 *
	 * @throws  StoreException      Error while adding the entry.
	 */
	public function add( StorableInterface $storable ) {
		if ( $this->has( $storable->get_id() ) ) {
			throw new StoreException( \sprintf( 'Entry %1$s already exists in store %2$s of type %3$s', $storable->get_id(), $this->get_id(), $this->get_storage_type() ) );
		}
		$this->update( $storable );
	}
	/**
	 * Updates (or adds if it doesn't exist) an entry in the store.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   StorableInterface   $storable   Object to add or update.
	 *
	 * @return  bool
	 */
	public function update( StorableInterface $storable ) : bool {
		$entry_id = $this->sanitize_entry_id( $storable->get_id() );
		return \update_option( $this->get_key(), \array_merge( $this->get_all(), array( $entry_id => $storable ) ) );
	}
	/**
	 * Removes an entry from the store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $entry_id   The identifier of the entry.
	 *
	 * @throws  NotFoundException   Thrown when the entry does not exist.
	 *
	 * @return  bool
	 */
	public function remove( string $entry_id ) : bool {
		if ( $this->has( $entry_id ) ) {
			$stored_objects = $this->get_all();
			$entry_id       = $this->sanitize_entry_id( $entry_id );
			unset( $stored_objects[ $entry_id ] );
			return empty( $stored_objects ) ? $this->empty() : \update_option( $this->get_key(), $stored_objects );
		}
		throw new NotFoundException( \sprintf( 'Could not delete entry %1$s. Not found in store %2$s of type %3$s', $entry_id, $this->get_id(), $this->get_storage_type() ) );
	}
	/**
	 * Removes all objects from the store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function empty() : bool {
		return \delete_option( $this->get_key() );
	}
	// endregion
	// region HELPERS
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
