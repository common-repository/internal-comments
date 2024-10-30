<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\Actions;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputtableExtensionTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\OutputtableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\HandlerInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\MultiHandlerAwareInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Output extension trait for outputting handlers in the same-go.
 *
 * @since   1.0.0
 * @version 1.1.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Services\Actions
 */
trait OutputHandlersTrait {

	// region TRAITS
	use OutputtableExtensionTrait;
	// endregion
	// region METHODS
	/**
	 * Makes one's own successful output dependent on that of one's handlers.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  OutputFailureException|null
	 */
	public function output_handlers() : ?OutputFailureException {
		$output_result = null;
		if ( $this instanceof HandlerAwareInterface || $this instanceof MultiHandlerAwareInterface ) {
			$handlers = $this instanceof HandlerAwareInterface ? array( $this->get_handler() ) : $this->get_handlers();
			$handlers = \array_filter(
				$handlers,
				function ( HandlerInterface $handler ) {
					return \is_a( $handler, OutputtableInterface::class );
				}
			);
			foreach ( $handlers as $handler ) {
				$output_result = $handler->output();
				if ( ! \is_null( $output_result ) ) {
					break;
				}
			}
		}
		return $output_result;
	}
	// endregion
}
