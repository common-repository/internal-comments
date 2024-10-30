<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunnableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
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
trait RunHandlersTrait {

	// region TRAITS
	use RunnableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * Makes one's own successful run dependent on that of one's handlers.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  RunFailureException|null
	 */
	public function run_handlers() : ?RunFailureException {
		$run_result = null;
		if ( $this instanceof HandlerAwareInterface || $this instanceof MultiHandlerAwareInterface ) {
			$handlers = $this instanceof HandlerAwareInterface ? array( $this->get_handler() ) : $this->get_handlers();
			$handlers = \array_filter(
				$handlers,
				function ( HandlerInterface $handler ) {
					return \is_a( $handler, RunnableInterface::class );
				}
			);
			foreach ( $handlers as $handler ) {
				$run_result = $handler->run();
				if ( ! \is_null( $run_result ) ) {
					break;
				}
			}
		}
		return $run_result;
	}
	// endregion
}
