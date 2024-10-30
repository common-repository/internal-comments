<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingServiceAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StorableInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes a service instance. Services are central hubs for performing actions that exist "outside" the plugin tree.
 * Typically the implementation logic should be left to a handler.
 *
 * @since   1.0.0
 * @version 1.3.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Services
 */
interface ServiceInterface extends PluginAwareInterface, LoggingServiceAwareInterface, StorableInterface {

	/* empty on purpose */
}
