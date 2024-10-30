<?php

namespace DeepWebSolutions\Plugins\InternalComments\Query;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\SetupableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\ChildInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\ChildTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Handlers\ScopedHooksHandler;

/**
 * Manages automatic hooking/unhooking of filters and actions during certain scopes.
 *
 * @since   1.0.0
 * @version 1.1.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class ScopedHooks extends ScopedHooksHandler implements ChildInterface, SetupableInterface {
	// region TRAITS

	use ChildTrait;
	use SetupLocalTrait;

	// endregion

	// region MAGIC METHODS

	/**
	 * ScopedHooks constructor.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 *
	 * @noinspection PhpMissingParentConstructorInspection
	 */
	public function __construct() {
		$this->parse_scope(
			array( 'hook' => dws_ic_get_hook_tag( 'comments', array( 'before_get_query' ) ) ),
			array( 'hook' => dws_ic_get_hook_tag( 'comments', array( 'after_get_query' ) ) )
		);
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Configures the scoped hooks list.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 *
	 * @return  SetupFailureException|null
	 */
	protected function setup_local(): ?SetupFailureException {
		$this->remove_filter( 'comments_clauses', dws_ic_component( 'query' ), 'exclude_internal_comments_from_query' );
		$this->add_filter( dws_ic_get_component_hook_tag( 'query', 'is_other_query' ), null, '__return_false' );

		return null;
	}

	// endregion
}
