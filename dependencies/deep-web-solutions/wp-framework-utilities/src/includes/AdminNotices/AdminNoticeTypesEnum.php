<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\AdminNotices;

\defined( 'ABSPATH' ) || exit;
/**
 * Valid values for admin notices types.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices
 */
class AdminNoticeTypesEnum {

	/**
	 * The slug of admin error notices.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const ERROR = 'error';
	/**
	 * The slug of admin warning notices.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const WARNING = 'warning';
	/**
	 * The slug of admin success notices.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const SUCCESS = 'success';
	/**
	 * The slug of admin info notices.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  public
	 * @var     string
	 */
	public const INFO = 'info';
	/**
	 * Returns an array of all valid admin notice types.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string[]
	 */
	public static function get_all() : array {
		return array( self::ERROR, self::WARNING, self::SUCCESS, self::INFO );
	}
}
