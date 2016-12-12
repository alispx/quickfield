<?php

/**
 * Image Select
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
 * Field Image Select.
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_image_select( $settings, $value ) {

	$output = '';

	$attrs = array();

	if ( !empty( $settings['name'] ) ) {
		$attrs[] = 'name="' . $settings['name'] . '"';
	}

	if ( !empty( $settings['id'] ) ) {
		$attrs[] = 'id="' . $settings['id'] . '"';
	}

	$attrs[] = 'data-type="' . $settings['type'] . '"';

	if ( !isset( $settings['inline'] ) ) {
		$settings['inline'] = 1;
	}

	if ( is_array( $settings['options'] ) ) {

		$br = $settings['inline'] ? '' : '<br/>';

		$output.= sprintf( '<div class="quickfield-field quickfield-image_select">' );

		foreach ( $settings['options'] as $radio_key => $image_url ) {

			$checked = $radio_key === $value ? 'checked' : '';

			$output.=sprintf( '<label><input class="qf_value" type="radio" value="%1$s" %3$s %4$s/><span><img alt="%1$s" src="%2$s"/></span></label>', $radio_key, $image_url, $checked, implode( ' ', $attrs ) );
			$output.=$br;
		}

		$output.= '</div>';
	}

	return $output;
}
