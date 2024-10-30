<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage;

use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Basic implementation of the store-container-aware interface.
 *
 * @since   1.0.0
 * @version 1.3.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Storage
 */
trait StoreAwareTrait {

	// region FIELDS AND CONSTANTS
	/**
	 * Store instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     StoreInterface
	 */
	protected StoreInterface $store;
	// endregion
	// region GETTERS
	/**
	 * Gets an instance of a store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  StoreInterface
	 */
	public function get_store() : StoreInterface {
		return $this->store;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets a store on the instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   StoreInterface      $store      Store to use from now on.
	 */
	public function set_store( StoreInterface $store ) {
		$this->store = $store;
	}
	// endregion
	// region HELPERS
	/**
	 * Returns an object from the store or null on failure.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   string  $entry_id   The ID of the entry to retrieve from the store.
	 *
	 * @return  StorableInterface|null
	 */
	public function get_store_entry( string $entry_id ) : ?StorableInterface {
		try {
			return $this->get_store()->get( $entry_id );
		} catch ( ContainerExceptionInterface | NotFoundExceptionInterface $exception ) {
			return null;
		}
	}
	/**
	 * Adds an object to the store. Returns false on failure.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   StorableInterface   $storable   Object to store.
	 *
	 * @return  bool
	 */
	public function add_store_entry( StorableInterface $storable ) : bool {
		try {
			$this->get_store()->add( $storable );
			return \true;
		} catch ( ContainerExceptionInterface $exception ) {
			return \false;
		}
	}
	/**
	 * Updates (or adds if it doesn't exist) an object to the store. Returns false on failure.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 *
	 * @param   StorableInterface   $storable   Object to update/add.
	 *
	 * @return  bool
	 */
	public function update_store_entry( StorableInterface $storable ) : bool {
		try {
			$this->get_store()->update( $storable );
			return \true;
		} catch ( ContainerExceptionInterface $exception ) {
			return \false;
		}
	}
	/**
	 * Removes an object to the store. Returns false on failure.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $entry_id   The ID of the entry to remove from the store.
	 *
	 * @return  bool
	 */
	public function remove_store_entry( string $entry_id ) : bool {
		try {
			$this->get_store()->remove( $entry_id );
			return \true;
		} catch ( ContainerExceptionInterface $exception ) {
			return \false;
		}
	}
	// endregion
}
