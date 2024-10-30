<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\Stores;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\AbstractStore;
\defined( 'ABSPATH' ) || exit;
/**
 * Reusable implementation of a basic in-memory store.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Storage\Stores
 */
class MemoryStore extends AbstractStore {

	// region TRAITS
	use MemoryStoreTrait;
	// endregion
}
