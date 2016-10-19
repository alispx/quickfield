<?php
/**
 * Color Picker
 *
 * @package   Quick_Field/Corefields
 * @category  Functions
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */


if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Field Color Picker.
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_color_picker( $settings, $value ) {

	$id = isset( $settings['id'] ) ? $settings['id'] : $settings['name'];

	$value = htmlspecialchars( $value );

	return sprintf( '<input type="text" name="%1$s" id="%2$s" class="quickfield-color %2$s %3$s" value="%4$s" data-default-color="%4$s" />', $settings['name'], $id, $settings['type'], $value );
}
