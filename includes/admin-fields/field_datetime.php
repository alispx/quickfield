<?php

/**
 * Map
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
 * Field datetime
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_datetime( $settings, $value = '' ) {

	$options = !empty( $settings['options'] ) ? $settings['options'] : array();

	$datetimepicker = wp_parse_args( $options, array(
		'format' => '',
		'datepicker' => 1,
		'timepicker' => 1,
		'mask' => 0,
		'inline' => 0,
			) );

	/**
	 * Css Class
	 */
	$css_class = 'quickfield-field quickfield-datetime';
	if ( !empty( $settings['el_class'] ) ) {
		$css_class.=' ' . $settings['el_class'];
	}

	/**
	 * Attributes
	 */
	$attrs = array();

	if ( !empty( $settings['name'] ) ) {
		$attrs[] = 'name="' . $settings['name'] . '"';
	}

	if ( !empty( $settings['id'] ) ) {
		$attrs[] = 'id="' . $settings['id'] . '"';
	}

	$attrs[] = 'data-type="' . $settings['type'] . '"';

	/**
	 * Support Customizer
	 */
	if ( !empty( $settings['customize_link'] ) ) {
		$attrs[] = $settings['customize_link'];
	}

	/**
	 * Input default attr
	 */
	$attrs[] = 'class="' . $css_class . '"';
	$attrs[] = 'value="' . $value . '"';

	/**
	 * Datetimepicker options
	 */
	if ( !empty( $datetimepicker ) ) {
		foreach ( $datetimepicker as $key => $val ) {
			if ( $val !== '' ) {
				$attrs[] = sprintf( 'data-%s="%s"', $key, $val );
			}
		}
	}

	return sprintf( '<div class="%s"><input class="qf_value" type="text" %s/><i class="fa fa-clock-o" aria-hidden="true"></i></div>', $css_class, implode( ' ', $attrs ) );
}
