<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance aware of multiple stores.
 *
 * @since   1.0.0
 * @version 1.5.3
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Storage
 */
interface MultiStoreAwareInterface {

	/**
	 * Gets all store instances set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  StoreInterface[]
	 */
	public function get_stores() : array;
	/**
	 * Replaces all stores set on the object with new ones.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   StoreInterface[]    $stores     Store instances to use from now on.
	 */
	public function set_stores( array $stores);
	/**
	 * Returns a given store set on the object by its ID.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $store_id   The ID of the store.
	 *
	 * @return  StoreInterface|null
	 */
	public function get_store( string $store_id) : ?StoreInterface;
	/**
	 * Registers a new store with the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   StoreInterface      $store      Store to register with the instance.
	 */
	public function register_store( StoreInterface $store);
}
