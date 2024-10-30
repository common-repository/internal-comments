<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Foundations;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializationFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializeLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\InitializableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingServiceAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingServiceAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\FileSystem\PathsTrait;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating some of the most often required abilities of a plugin instance.
 *
 * @since   1.0.0
 * @version 1.6.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Foundations
 */
abstract class AbstractPlugin implements PluginInterface, InitializableInterface, LoggingServiceAwareInterface {

	// region TRAITS
	use InitializeLocalTrait;
	use LoggingServiceAwareTrait;
	use PathsTrait;
	use PluginTrait;
	// endregion
	// region MAGIC METHODS
	/**
	 * AbstractPlugin constructor.
	 *
	 * @since   1.6.0
	 * @version 1.6.0
	 *
	 * @param   string  $plugin_slug    The plugin slug.
	 */
	public function __construct( string $plugin_slug ) {
		$this->plugin_slug = $plugin_slug;
	}
	// endregion
	// region INHERITED METHODS
	/**
	 * Uses the plugin file path to initialize the plugin data fields.
	 *
	 * @since   1.0.0
	 * @version 1.6.0
	 *
	 * @return  InitializationFailureException|null
	 */
	public function initialize_local() : ?InitializationFailureException {
		$plugin_data                  = \get_file_data(
			$this->get_plugin_file_path(),
			array(
				'Name'        => 'Plugin Name',
				'Version'     => 'Version',
				'Description' => 'Description',
				'Author'      => 'Author',
				'AuthorURI'   => 'Author URI',
				'TextDomain'  => 'Text Domain',
			),
			'plugin'
		);
		$this->plugin_name            = $plugin_data['Name'];
		$this->plugin_version         = $plugin_data['Version'];
		$this->plugin_description     = $plugin_data['Description'];
		$this->plugin_author_name     = $plugin_data['Author'];
		$this->plugin_author_uri      = $plugin_data['AuthorURI'];
		$this->plugin_language_domain = $plugin_data['TextDomain'];
		return null;
	}
	// endregion
}
