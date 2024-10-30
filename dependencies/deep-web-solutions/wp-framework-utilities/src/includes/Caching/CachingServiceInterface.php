<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\ServiceInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance of a cache service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Caching
 */
interface CachingServiceInterface extends ServiceInterface, CachingAdapterInterface {

	/* empty on purpose */
}
