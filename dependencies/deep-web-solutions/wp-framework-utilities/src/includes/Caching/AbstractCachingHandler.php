<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Caching;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractHandler;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating some of the most often needed functionality of a caching handler.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Caching
 */
abstract class AbstractCachingHandler extends AbstractHandler implements CachingHandlerInterface, PluginAwareInterface {

	// region TRAITS
	use PluginAwareTrait;
	// endregion
	// region INHERITED METHODS
	/**
	 * Returns the type of the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_type() : string {
		return 'caching';
	}
	// endregion
}
