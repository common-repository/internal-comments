<?php
/**
 * Admin view: post types setting single checkbox.
 *
 * @since   1.2.0
 * @version 1.2.0
 * @package DeepWebSolutions\WP-Plugins\InternalComments\Settings
 *
 * @var     string  $id         Checkbox ID.
 * @var     string  $name       Checkbox name.
 * @var     string  $value      Checkbox value.
 * @var     string  $checked    Checkbox checked attr.
 * @var     string  $label      Checkbox label.
 */

defined( 'ABSPATH' ) || exit; ?>

<label>
	<input id="<?php echo esc_attr( $id ); ?>" type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo $checked; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>/>
	&nbsp;<?php echo esc_html( $label ); ?>
</label><br/>
