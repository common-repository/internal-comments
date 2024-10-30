<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\OutputtableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StoreAwareInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes a way to store, retrieve and output admin notices.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices
 */
interface AdminNoticesHandlerInterface extends HandlerInterface, StoreAwareInterface, OutputtableInterface {

	/**
	 * Returns all the stored notices of the handler's type within a given store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $store_id   The name of the store to retrieve the notices from.
	 *
	 * @return  AdminNoticeInterface[]
	 */
	public function get_notices( string $store_id) : array;
	/**
	 * Returns a given notice from a given store as long as it's of the handler's type.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $store_id   The ID of the store holding the notice.
	 * @param   string  $handle     The ID of the notice to retrieve.
	 *
	 * @return  AdminNoticeInterface|null
	 */
	public function get_notice( string $store_id, string $handle) : ?AdminNoticeInterface;
}
