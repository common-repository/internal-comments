<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage;

use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\ContainerInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes a store instance. Stores are objects that can perform READ/UPDATE/DELETE operations against a storage medium
 * like RAM or the database.
 *
 * @since   1.0.0
 * @version 1.3.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Storage
 */
interface StoreInterface extends ContainerInterface, StorableInterface {

	// region METHODS
	/**
	 * Returns the storage medium of the store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_storage_type() : string;
	/**
	 * Returns the total number of entries stored.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  int
	 */
	public function count() : int;
	// endregion
	// region CRUD
	/**
	 * Returns all the objects stored.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @throws  ContainerExceptionInterface     Error while retrieving the entries.
	 *
	 * @return  StorableInterface[]
	 */
	public function get_all() : array;
	/**
	 * Adds an object to the store.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   StorableInterface   $storable   Object to store.
	 *
	 * @throws  ContainerExceptionInterface     Error while adding the entry.
	 */
	public function add( StorableInterface $storable);
	/**
	 * Updates (or adds if it doesn't exist) an object in the store.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   StorableInterface   $storable   Object to store.
	 *
	 * @throws  ContainerExceptionInterface     Error while updating the entry.
	 */
	public function update( StorableInterface $storable);
	/**
	 * Removes an object from the store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $entry_id   The identifier of the entry.
	 *
	 * @throws  NotFoundExceptionInterface      No entry was found for **this** identifier.
	 * @throws  ContainerExceptionInterface     Error while deleting the entry.
	 */
	public function remove( string $entry_id);
	/**
	 * Removes all objects from the store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  ContainerExceptionInterface     Error while deleting the entries.
	 */
	public function empty();
	// endregion
}
