<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions;

\defined( 'ABSPATH' ) || exit;
/**
 * Describes an instance that can be outputted in a certain format. Implementing classes need to define an output-generating logic.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations\Actions
 */
interface OutputtableInterface {

	/**
	 * Should be called when the implementing class should generate its output.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  Outputtable\OutputFailureException|null
	 */
	public function output() : ?Outputtable\OutputFailureException;
}
