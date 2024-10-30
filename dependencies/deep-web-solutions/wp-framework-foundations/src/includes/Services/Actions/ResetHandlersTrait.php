<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable\ResetFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Resettable\ResettableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\ResettableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\MultiHandlerAwareInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Run extension trait for running handlers in the same-go.
 *
 * @since   1.0.0
 * @version 1.1.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Services\Actions
 */
trait ResetHandlersTrait {

	// region TRAITS
	use ResettableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * Makes one's own successful reset dependent on that of one's handlers.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ResetFailureException|null
	 */
	public function reset_handlers() : ?ResetFailureException {
		$reset_result = null;
		if ( $this instanceof HandlerAwareInterface || $this instanceof MultiHandlerAwareInterface ) {
			$handlers = $this instanceof HandlerAwareInterface ? array( $this->get_handler() ) : $this->get_handlers();
			$handlers = \array_filter(
				$handlers,
				function ( HandlerInterface $handler ) {
					return \is_a( $handler, ResettableInterface::class );
				}
			);
			foreach ( $handlers as $handler ) {
				$reset_result = $handler->reset();
				if ( ! \is_null( $reset_result ) ) {
					break;
				}
			}
		}
		return $reset_result;
	}
	// endregion
}
