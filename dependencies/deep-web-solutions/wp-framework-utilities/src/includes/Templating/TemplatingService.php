<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Templating;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\HooksHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Services\AbstractService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\FileSystem\FilesystemAwareTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\HooksHelpersAwareInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Request;
use DWS_IC_Deps\Psr\Log\LogLevel;
\defined( 'ABSPATH' ) || exit;
/**
 * Retrieves PHP template files either as HTML string or by loading them into the execution flow.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Templating
 */
class TemplatingService extends AbstractService implements HooksHelpersAwareInterface {

	// region TRAITS
	use FilesystemAwareTrait;
	use HooksHelpersTrait;
	// endregion
	// region METHODS
	/**
	 * Requires a template file part.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $slug           Template slug.
	 * @param   string  $name           Template name. Pass an empty string to ignore.
	 * @param   string  $template_path  The relative path of the template from the root of the active theme.
	 * @param   string  $default_path   The absolute path to the template's folder within the plugin.
	 * @param   array   $args           Arguments to pass on to the template.
	 * @param   string  $constant_name  The name of the constant that should evaluate to true for debugging to be considered active.
	 *
	 * @return  void
	 */
	public function load_template_part( string $slug, string $name, string $template_path, string $default_path, array $args = array(), string $constant_name = 'TEMPLATE_DEBUG' ) : void {
		$template = ! empty( $name ) ? $this->locate_template( "{$slug}-{$name}.php", $template_path, $default_path, $constant_name ) : $this->locate_template( "{$slug}.php", $template_path, $default_path, $constant_name );
		// Allow 3rd-party plugins to filter the template file from their plugin.
		$filtered_template = \apply_filters( $this->get_hook_tag( 'get_template_part' ), $template, $slug, $name, $template_path, $default_path, $args, $constant_name );
        // phpcs:ignore
        $template = $this->maybe_overwrite_template($filtered_template, $template);
		if ( \is_null( $template ) ) {
			return;
		}
		// Load the found template part.
		\do_action( $this->get_hook_tag( 'before_template_part' ), $slug, $name, $template_path, $template, $args, $constant_name );
		\load_template( $template, \false, $args );
		\do_action( $this->get_hook_tag( 'after_template_part' ), $slug, $name, $template_path, $template, $args, $constant_name );
	}
	/**
	 * Returns the content of a template part as an HTML string.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $slug           Template slug.
	 * @param   string  $name           Template name. Pass an empty string to ignore.
	 * @param   string  $template_path  The relative path of the template from the root of the active theme.
	 * @param   string  $default_path   The absolute path to the template's folder within the plugin.
	 * @param   array   $args           Arguments to pass on to the template.
	 * @param   string  $constant_name  The name of the constant that should evaluate to true for debugging to be considered active.
	 *
	 * @return  string
	 */
	public function get_template_part_html( string $slug, string $name, string $template_path, string $default_path, array $args = array(), string $constant_name = 'TEMPLATE_DEBUG' ) : string {
		\ob_start();
		$this->load_template_part( $slug, $name, $template_path, $default_path, $args, $constant_name );
		return \ob_get_clean();
	}
	/**
	 * Requires a template file.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $template_name  The name of the template file searched for.
	 * @param   string  $template_path  The relative path of the template from the root of the active theme.
	 * @param   string  $default_path   The absolute path to the template's folder within the plugin.
	 * @param   array   $args           Arguments to pass on to the template.
	 * @param   string  $constant_name  The name of the constant that should evaluate to true for debugging to be considered active.
	 *
	 * @return  void
	 */
	public function load_template( string $template_name, string $template_path, string $default_path, array $args = array(), string $constant_name = 'TEMPLATE_DEBUG' ) : void {
		$template = self::locate_template( $template_name, $template_path, $default_path, $constant_name );
		// Allow 3rd-party plugins to filter the template file from their plugin.
		$filtered_template = \apply_filters( $this->get_hook_tag( 'get_template' ), $template, $template_name, $template_path, $default_path, $args, $constant_name );
        // phpcs:ignore
        $template = $this->maybe_overwrite_template($filtered_template, $template);
		if ( \is_null( $template ) ) {
			return;
		}
		// Load the found template.
		\do_action( $this->get_hook_tag( 'before_template' ), $template_name, $template_path, $template, $args, $constant_name );
		\load_template( $template, \false, $args );
		\do_action( $this->get_hook_tag( 'after_template' ), $template_name, $template_path, $template, $args, $constant_name );
	}
	/**
	 * Returns the content of a template as an HTML string.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $template_name  The name of the template file searched for.
	 * @param   string  $template_path  The relative path of the template from the root of the active theme.
	 * @param   string  $default_path   The absolute path to the template's folder within the plugin.
	 * @param   array   $args           Arguments to pass on to the template.
	 * @param   string  $constant_name  The name of the constant that should evaluate to true for debugging to be considered active.
	 *
	 * @return  string
	 */
	public function get_template_html( string $template_name, string $template_path, string $default_path, array $args = array(), string $constant_name = 'TEMPLATE_DEBUG' ) : string {
		\ob_start();
		$this->load_template( $template_name, $template_path, $default_path, $args, $constant_name );
		return \ob_get_clean();
	}
	/**
	 * Returns the path to a template file. If the theme overwrites the file and debugging is disabled, returns the path
	 * to the theme's file, otherwise the path to the default file packaged with the plugin.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $template_name  The name of the template file searched for.
	 * @param   string  $template_path  The relative path of the template from the root of the active theme.
	 * @param   string  $default_path   The absolute path to the template's folder within the plugin.
	 * @param   string  $constant_name  The name of the constant that should evaluate to true for debugging to be considered active.
	 *
	 * @return  string
	 */
	public function locate_template( string $template_name, string $template_path, string $default_path, string $constant_name = 'TEMPLATE_DEBUG' ) : string {
		$template = Request::has_debug( $constant_name ) ? '' : \locate_template( array( \trailingslashit( $template_path ) . $template_name, $template_name ) );
		$template = empty( $template ) ? \trailingslashit( $default_path ) . $template_name : $template;
		return \apply_filters( $this->get_hook_tag( 'locate_template' ), $template, $template_name, $template_path, $default_path, $constant_name );
	}
	// endregion
	// region HELPERS
	/**
	 * Checks if the filtered template path exists, and if so, returns it, otherwise returns null.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $filtered_template  The template path after running a filter on it.
	 * @param   string  $template           The original template path from before the filter.
	 *
	 * @return  string|null
	 */
	protected function maybe_overwrite_template( string $filtered_template, string $template ) : ?string {
		if ( $filtered_template !== $template ) {
			if ( ! $this->get_wp_filesystem()->exists( $filtered_template ) ) {
				return $this->log_event(
					/* translators: %s: Path to template file */
					\sprintf( 'The file %s does not exist.', "<code>{$filtered_template}</code>" ),
					array(),
					'framework'
				)->set_log_level( LogLevel::ERROR )->doing_it_wrong( __FUNCTION__, '1.0.0' )->finalize();
			}
			$template = $filtered_template;
		}
		return $template;
	}
	// endregion
}
