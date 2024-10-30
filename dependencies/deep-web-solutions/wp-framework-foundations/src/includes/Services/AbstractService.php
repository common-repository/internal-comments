<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingServiceAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating some of the most often required abilities of a service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Services
 */
abstract class AbstractService implements ServiceInterface {

	// region TRAITS
	use LoggingServiceAwareTrait;
	use PluginAwareTrait;
	// endregion
	// region MAGIC METHODS
	/**
	 * AbstractService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   PluginInterface     $plugin             Instance of the plugin.
	 * @param   LoggingService      $logging_service    Instance of the logging service.
	 */
	public function __construct( PluginInterface $plugin, LoggingService $logging_service ) {
		$this->set_plugin( $plugin );
		$this->set_logging_service( $logging_service );
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * Returns the ID of the instance. Since services are supposed to be singletons,
	 * this is a safe default.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_id() : string {
		return static::class;
	}
	// endregion
}
