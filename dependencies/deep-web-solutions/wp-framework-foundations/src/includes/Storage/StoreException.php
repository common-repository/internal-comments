<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage;

use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Thrown when a store encounters an unsupported scenario or an unexpected error.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Storage
 */
class StoreException extends \Exception implements ContainerExceptionInterface {

	/* empty on purpose */
}
