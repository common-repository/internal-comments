<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\OutputtableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Storage\StorableInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Describes an admin notice.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices
 */
interface AdminNoticeInterface extends StorableInterface, OutputtableInterface {

	/**
	 * Whether the notice is persistent or not. Non-persistent notices should be deleted from the store
	 * after their first output.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function is_persistent() : bool;
	/**
	 * Checks whether the notice should be outputted or not. Your implementation is free
	 * to ignore this result.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool    Whether the notice should be outputted or not.
	 */
	public function should_output() : bool;
	/**
	 * Outputs the notice inline as HTML.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  OutputFailureException|null
	 */
	public function output() : ?OutputFailureException;
}
