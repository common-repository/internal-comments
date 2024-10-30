<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StoreAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StoreInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\Stores\MemoryStore;
\defined( 'ABSPATH' ) || exit;
/**
 * Encapsulates the most often needed functionality of a notices handler.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices
 */
abstract class AbstractAdminNoticesHandler implements AdminNoticesHandlerInterface {

	// region TRAITS
	use OutputLocalTrait;
	use StoreAwareTrait;
	// endregion
	// region FIELDS AND CONSTANTS
	/**
	 * Whether any user notices have been outputted during the current request.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool
	 */
	protected bool $has_output = \false;
	// endregion
	// region MAGIC METHODS
	/**
	 * AbstractAdminNoticesHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   MemoryStore|null        $admin_notices_stores       Store containing the valid admin notices stores.
	 */
	public function __construct( ?MemoryStore $admin_notices_stores = null ) {
		if ( ! \is_null( $admin_notices_stores ) ) {
			$this->set_store( $admin_notices_stores );
		}
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_type() : string {
		return 'admin-notices';
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_notice( string $store_id, string $handle ) : ?AdminNoticeInterface {
		$store = $this->get_store_entry( $store_id );
		if ( ! $store instanceof StoreInterface ) {
			return null;
		}
		$notice = $store->get( $handle );
		return \get_class( $notice ) === $this->get_id() ? $notice : null;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_notices( string $store_id ) : array {
		$store = $this->get_store_entry( $store_id );
		return $store instanceof StoreInterface ? \array_filter( $store->get_all(), fn( AdminNoticeInterface $notice) => \get_class( $notice ) === $this->get_id() ) : array();
	}
	/**
	 * Output all admin notices handled by the handler instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  OutputFailureException|null
	 */
	protected function output_local() : ?OutputFailureException {
		foreach ( $this->get_store()->get_all() as $admin_notices_store ) {
			if ( $admin_notices_store instanceof StoreInterface ) {
				foreach ( $this->get_notices( $admin_notices_store->get_id() ) as $notice ) {
					$result = $this->output_notice( $notice, $admin_notices_store );
					if ( ! \is_null( $result ) ) {
						return $result;
					}
					$this->has_output = \true;
					if ( ! $notice->is_persistent() ) {
						$admin_notices_store->remove( $notice->get_id() );
					}
				}
			}
		}
		return null;
	}
	// endregion
	// region HELPERS
	/**
	 * Allows notice output manipulation by inheriting handlers. By default, just calls the output method of the notice.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @param   AdminNoticeInterface    $notice     Notice to output.
	 * @param   StoreInterface          $store      Store holding the notice.
	 *
	 * @return  OutputFailureException|null
	 */
	protected function output_notice( AdminNoticeInterface $notice, StoreInterface $store ) : ?OutputFailureException {
		return $notice->output();
	}
	// endregion
}
