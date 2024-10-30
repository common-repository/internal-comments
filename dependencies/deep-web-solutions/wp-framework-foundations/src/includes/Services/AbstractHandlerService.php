<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\DependencyInjection\ContainerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingServiceAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating some of the most often required abilities of a handler service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Services
 */
abstract class AbstractHandlerService extends AbstractService implements ServiceInterface, HandlerAwareInterface {

	// region TRAITS
	use HandlerAwareTrait {
		set_handler as protected set_handler_trait;
	}
	// endregion
	// region MAGIC METHODS
	/**
	 * AbstractHandlerService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   PluginInterface         $plugin             Instance of the plugin.
	 * @param   LoggingService          $logging_service    Instance of the logging service.
	 * @param   HandlerInterface|null   $handler            Instance of the handler used.
	 */
	public function __construct( PluginInterface $plugin, LoggingService $logging_service, ?HandlerInterface $handler = null ) {
		parent::__construct( $plugin, $logging_service );
		$this->set_default_handler( $handler );
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function set_handler( HandlerInterface $handler ) : bool {
		if ( ! \is_a( $handler, $this->get_handler_class() ) ) {
			return \false;
		}
		if ( $handler instanceof PluginAwareInterface ) {
			$handler->set_plugin( $this->get_plugin() );
		}
		if ( $handler instanceof LoggingServiceAwareInterface ) {
			$handler->set_logging_service( $this->get_logging_service() );
		}
		$this->set_handler_trait( $handler );
		return \true;
	}
	// endregion
	// region HELPERS
	/**
	 * Registers a default handler if one has not been set through the container.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HandlerInterface|null       $handler    Handler passed on in the constructor.
	 *
	 * @throws  NotFoundExceptionInterface      Thrown if the NullLogger is not found in the plugin DI-container.
	 * @throws  ContainerExceptionInterface     Thrown if some other error occurs while retrieving the NullLogger instance.
	 */
	protected function set_default_handler( ?HandlerInterface $handler ) : void {
		if ( ! \is_a( $handler, $this->get_handler_class() ) ) {
			$handler_class = $this->get_default_handler_class();
			if ( \is_null( $handler_class ) ) {
				$handler = null;
			} else {
				$plugin  = $this->get_plugin();
				$handler = $plugin instanceof ContainerAwareInterface ? $plugin->get_container()->get( $handler_class ) : new $handler_class();
			}
		}
		if ( ! \is_null( $handler ) ) {
			$this->set_handler( $handler );
		}
	}
	/**
	 * Returns the class name of the default handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string|null
	 */
	protected function get_default_handler_class() : ?string {
		return null;
	}
	/**
	 * Returns the class name of the used handler for better type-checking.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	abstract protected function get_handler_class() : string;
	// endregion
}
