<?php

namespace DWS_IC_Deps;

/**
 * A very early error message displayed if environment requirements are not met.
 *
 * @since   1.0.0
 * @version 1.3.0
 * @package DeepWebSolutions\WP-Framework\Bootstrapper
 *
 * @see     dws_wp_framework_output_requirements_error
 *
 * @var     string $component_name
 * @var     string $component_version
 * @var     string $min_php_version
 * @var     string $min_wp_version
 * @var     array  $args
 */
\defined( 'ABSPATH' ) || exit;
?>

<?php
do_action( 'dws_wp_framework/bootstrapper/requirements_error_notice/before', $component_name, $component_version, $min_php_version, $min_wp_version, $args );
?>

<div class="error notice dws-requirements-error">
	<?php
	do_action( 'dws_wp_framework/bootstrapper/requirements_error_notice/start', $component_name, $component_version, $min_php_version, $min_wp_version, $args );
	?>

	<p>
		<?php
		echo wp_kses_post(
			wp_sprintf(
			/* translators: 1. Component name, 2. Component version */
				__( '<strong>%1$s (%2$s)</strong> has encountered an error. Your environment doesn\'t meet all the system requirements listed below:', 'internal-comments' ),
				$component_name,
				$component_version
			)
		);
		?>
	</p>

	<?php
	do_action( 'dws_wp_framework/bootstrapper/requirements_error_list/before', $component_name, $component_version, $min_php_version, $min_wp_version, $args );
	?>

	<ul class="ul-disc">
		<?php
		do_action( 'dws_wp_framework/bootstrapper/requirements_error_list/start', $component_name, $component_version, $min_php_version, $min_wp_version, $args );
		?>

		<li>
			<strong>PHP 
			<?php
			echo esc_html( $min_php_version );
			?>
			+</strong>
			<em>
			<?php
			echo esc_html(
				wp_sprintf(
				/* translators: PHP version */
					__( 'You\'re running version %s', 'internal-comments' ),
					\PHP_VERSION
				)
			);
			?>
			</em>
		</li>
		<li>
			<strong>WordPress 
			<?php
			echo esc_html( $min_wp_version );
			?>
			+</strong>
			<em>
			<?php
			echo esc_html(
				wp_sprintf(
				/* translators: WordPress version */
					__( 'You\'re running version %s', 'internal-comments' ),
					$GLOBALS['wp_version']
				)
			);
			?>
			</em>
		</li>

		<?php
		do_action( 'dws_wp_framework/bootstrapper/requirements_error_list/end', $component_name, $min_php_version, $min_wp_version, $args );
		?>
	</ul>

	<?php
	do_action( 'dws_wp_framework/bootstrapper/requirements_error_list/after', $component_name, $min_php_version, $min_wp_version, $args );
	?>

	<p>
		<?php
		echo wp_kses_post( __( 'If you need to upgrade your version of PHP you can ask your hosting company for assistance, and if you need help upgrading WordPress you can refer to <a href="https://wordpress.org/support/article/updating-wordpress/" target="_blank">the Codex</a>.', 'internal-comments' ) );
		?>
	</p>

	<?php
	do_action( 'dws_wp_framework/bootstrapper/requirements_error_notice/end', $component_name, $component_version, $min_php_version, $min_wp_version, $args );
	?>
</div>

<?php
do_action( 'dws_wp_framework/bootstrapper/requirements_error_notice/after', $component_name, $component_version, $min_php_version, $min_wp_version, $args );
