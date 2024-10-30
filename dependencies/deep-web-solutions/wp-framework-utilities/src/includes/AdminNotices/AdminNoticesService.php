<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\OutputtableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractMultiHandlerService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\Actions\OutputHandlersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StoreAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StoreInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\Stores\MemoryStore;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\Stores\OptionsStore;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\Stores\UserMetaStore;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Handlers\DismissibleNoticesHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Handlers\SimpleNoticesHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterTrait;
use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Compatibility layer between the framework and WordPress' API for admin notices.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices
 */
class AdminNoticesService extends AbstractMultiHandlerService implements HooksServiceAwareInterface, OutputtableInterface {

	// region TRAITS
	use HooksServiceAwareTrait;
	use HooksServiceRegisterTrait;
	use OutputHandlersTrait;
	// endregion
	// region MAGIC METHODS
	/**
	 * AdminNoticesService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   PluginInterface                 $plugin             Instance of the plugin.
	 * @param   LoggingService                  $logging_service    Instance of the logging service.
	 * @param   HooksService                    $hooks_service      Instance of the hooks service.
	 * @param   StoreInterface[]                $stores             Stores containing admin notices.
	 * @param   AdminNoticesHandlerInterface[]  $handlers           Admin notices handlers to output.
	 */
	public function __construct( PluginInterface $plugin, LoggingService $logging_service, HooksService $hooks_service, array $stores = array(), array $handlers = array() ) {
		$this->set_plugin( $plugin );
		$this->set_hooks_service( $hooks_service );
		$this->register_hooks( $hooks_service );
		$this->set_default_stores( $stores );
		parent::__construct( $plugin, $logging_service, $handlers );
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_handler( HandlerInterface $handler ) : AdminNoticesService {
		parent::register_handler( $handler );
		if ( $handler instanceof HooksServiceRegisterInterface ) {
			$handler->register_hooks( $this->get_hooks_service() );
		}
		if ( $handler instanceof StoreAwareInterface ) {
			$handler->set_store( $this->get_store( 'admin-notices-stores' ) );
		}
		return $this;
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_hooks( HooksService $hooks_service ) : void {
		$hooks_service->add_action( 'admin_notices', $this, 'output' );
	}
	// endregion
	// region METHODS
	/**
	 * Returns a given admin notices store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $store_id   The ID of the admin notices store to retrieve.
	 *
	 * @return  StoreInterface
	 */
	public function get_notices_store( string $store_id ) : StoreInterface {
		return $this->get_store( 'admin-notices-stores' )->get( $store_id );
	}
	/**
	 * Adds a notice notices to a given store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   AdminNoticeInterface    $notice     Notice to add to the store.
	 * @param   string                  $store_id   The ID of the store to add the notice to.
	 *
	 * @return  bool
	 */
	public function add_notice( AdminNoticeInterface $notice, string $store_id = 'dynamic' ) : bool {
		try {
			$result = $this->get_notices_store( $store_id )->add( $notice );
			return \is_null( $result ) || $result;
		} catch ( ContainerExceptionInterface $exception ) {
			return \false;
		}
	}
	/**
	 * Retrieves a notice from the given store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handle     Handle of the notice to retrieve.
	 * @param   string  $store_id   The ID of the store to add the notice to.
	 *
	 * @return  AdminNoticeInterface|null
	 */
	public function get_notice( string $handle, string $store_id = 'dynamic' ) : ?AdminNoticeInterface {
		try {
			return $this->get_notices_store( $store_id )->get( $handle );
		} catch ( ContainerExceptionInterface $exception ) {
			return null;
		}
	}
	/**
	 * Updates (or adds if it doesn't exist) a notice to the given store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   AdminNoticeInterface    $notice     Notice to add to the store.
	 * @param   string                  $store_id   The ID of the store to add the notice to.
	 *
	 * @return  bool
	 */
	public function update_notice( AdminNoticeInterface $notice, string $store_id = 'dynamic' ) : bool {
		try {
			$result = $this->get_notices_store( $store_id )->update( $notice );
			return \is_null( $result ) || $result;
		} catch ( ContainerExceptionInterface $exception ) {
			return \false;
		}
	}
	/**
	 * Removes a notice from a given store.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handle         Handle of the notice to remove.
	 * @param   string  $store_id       The ID of the store to remove the notice from.
	 *
	 * @return  bool    Whether the operation was successful or not.
	 */
	public function remove_notice( string $handle, string $store_id = 'dynamic' ) : bool {
		try {
			$result = $this->get_notices_store( $store_id )->remove( $handle );
			return \is_null( $result ) || $result;
		} catch ( NotFoundExceptionInterface $exception ) {
			return \true;
		} catch ( ContainerExceptionInterface $exception ) {
			return \false;
		}
	}
	// endregion
	// region HELPERS
	/**
	 * Register the stores passed on in the constructor together with the default stores.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   StoreInterface[]    $stores     Custom stores passed on through the constructor.
	 */
	protected function set_default_stores( array $stores ) : void {
		$database_key         = "_{$this->get_plugin()->get_plugin_safe_slug()}_admin_notices";
		$default_stores       = array( new MemoryStore( 'dynamic' ), new OptionsStore( 'options', $database_key ), new UserMetaStore( 'user-meta', $database_key ) );
		$notices_stores_store = new MemoryStore( 'admin-notices-stores' );
		foreach ( \array_merge( $default_stores, $stores ) as $store ) {
			if ( $store instanceof StoreInterface ) {
				$notices_stores_store->update( $store );
			}
		}
		$this->register_store( $notices_stores_store );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_default_handlers_classes() : array {
		return array( SimpleNoticesHandler::class, DismissibleNoticesHandler::class );
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_handler_class() : string {
		return AdminNoticesHandlerInterface::class;
	}
	// endregion
}
