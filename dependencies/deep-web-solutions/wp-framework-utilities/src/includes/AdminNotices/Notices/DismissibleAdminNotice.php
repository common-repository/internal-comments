<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices\Notices;

\defined( 'ABSPATH' ) || exit;
/**
 * Models dismissible notice.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices\Notices
 */
class DismissibleAdminNotice extends SimpleAdminNotice {

	// region FIELDS AND CONSTANTS
	/**
	 * Stores whether the notice is dismissed or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool
	 */
	protected bool $is_dismissed = \false;
	// endregion
	// region GETTERS
	/**
	 * Returns whether the notice is dismissed or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function is_dismissed() : bool {
		return $this->is_dismissed;
	}
	// endregion
	// region SETTERS
	/**
	 * Sets the notice's dismissed status.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   bool    $is_dismissed   Whether the notice is dismissed or not.
	 */
	public function set_dismissed( bool $is_dismissed ) : void {
		$this->is_dismissed = $is_dismissed;
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function should_output() : bool {
		return parent::should_output() && ! $this->is_dismissed();
	}
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_classes() : array {
		return \array_merge( parent::get_classes(), array( 'is-dismissible' ) );
	}
	// endregion
}
