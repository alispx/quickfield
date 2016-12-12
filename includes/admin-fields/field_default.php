<?php

/**
 * Default Fields
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
 * Field Textfield.
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_textfield( $settings, $value ) {

	$attrs = array();

	if ( !empty( $settings['name'] ) ) {
		$attrs[] = 'name="' . $settings['name'] . '"';
	}

	if ( !empty( $settings['id'] ) ) {
		$attrs[] = 'id="' . $settings['id'] . '"';
	}

	$attrs[] = 'data-type="' . $settings['type'] . '"';

	return sprintf( '<input type="text" class="quickfield-field quickfield-textfield widefat qf_value" value="%s" %s/>', htmlspecialchars( $value ), implode( ' ', $attrs ) );
}

/**
 * Field Textarea
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_textarea( $settings, $value ) {

	$attrs = array();

	if ( !empty( $settings['name'] ) ) {
		$attrs[] = 'name="' . $settings['name'] . '"';
	}

	if ( !empty( $settings['id'] ) ) {
		$attrs[] = 'id="' . $settings['id'] . '"';
	}

	$attrs[] = 'data-type="' . $settings['type'] . '"';

	return sprintf( '<textarea class="quickfield-field quickfield-textarea widefat qf_value" %s>%s</textarea>', implode( ' ', $attrs ), esc_textarea( $value ) );
}

/**
 * Field Checkbox
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_checkbox( $settings, $value = '' ) {

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

	if ( is_array( $value ) ) {
		$value = implode( ',', $value );
	}

	$multiple = isset( $settings['multiple'] ) && $settings['multiple'] ? 1 : 0;

	$output = '';

	if ( $multiple ) {

		if ( is_array( $settings['options'] ) ) {

			$inline = isset( $settings['display_inline'] ) && absint( $settings['display_inline'] ) ? 'inline' : '';

			$arr_values = !empty( $value ) ? explode( ',', sanitize_text_field( $value ) ) : array();

			$output.=sprintf( '<input type="hidden" class="qf_value" value="%s" %s/>', $value, implode( ' ', $attrs ) );

			$output.= sprintf( '<ul class="quickfield-field quickfield-checkboxes %s">', $inline );

			foreach ( $settings['options'] as $checkbox_key => $checkbox_value ) {

				$checked = in_array( $checkbox_key, $arr_values ) ? 'checked' : '';

				$output.=sprintf( '<li><label><input %s type="checkbox" value="%s"/><span>%s</span></label></li>', $checked, $checkbox_key, $checkbox_value );
			}

			$output.= '</ul>';
		}
	} else {

		if ( $value ) {
			$attrs[] = 'checked';
		}
		$output.=sprintf( '<input type="checkbox" value="1" class="quickfield-field quickfield-checkbox qf_value" %s/>', implode( ' ', $attrs ) );
	}

	return $output;
}

/**
 * Field Select
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_select( $settings, $value = '' ) {

	$multiple = isset( $settings['multiple'] ) && $settings['multiple'] ? 'multiple' : '';

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

	$css_class = 'quickfield-field quickfield-select quickfield-select-multiple';
	if ( !empty( $settings['el_class'] ) ) {
		$css_class.=' ' . $settings['el_class'];
	}

	if ( is_array( $value ) ) {
		$value = implode( ',', $value );
	}

	$output = '';
	if ( !empty( $multiple ) ) {
		$output.=sprintf( '<input type="hidden" class="qf_value" value="%s" %s/>', esc_attr( $value ), implode( ' ', $attrs ) );
		$output.= '<select multiple="" class="' . $css_class . '">';
		$value = !empty( $value ) ? explode( ',', $value ) : array();
	} else {
		$output.= sprintf( '<select class="quickfield-field quickfield-select qf_value" %s>', implode( ' ', $attrs ) );
	}

	if ( is_array( $settings['options'] ) ) {
		foreach ( $settings['options'] as $option_key => $option_value ) {

			if ( is_array( $value ) ) {
				$selected = in_array( $option_key, $value ) ? 'selected' : '';
			} else {
				$selected = $option_key === $value ? 'selected' : '';
			}

			$output.=sprintf( '<option %s value="%s">%s</option>', $selected, $option_key, $option_value );
		}
	}

	$output .= '</select>';

	return $output;
}

/**
 * Field Radio
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_radio( $settings, $value ) {

	$attrs = array();

	if ( !empty( $settings['name'] ) ) {
		$attrs[] = 'name="' . $settings['name'] . '"';
	}

	if ( !empty( $settings['id'] ) ) {
		$attrs[] = 'id="' . $settings['id'] . '"';
	}

	$attrs[] = 'data-type="' . $settings['type'] . '"';

	$output = '';

	if ( is_array( $settings['options'] ) ) {

		$inline = isset( $settings['display_inline'] ) && absint( $settings['display_inline'] ) ? 'inline' : '';

		$output.= sprintf( '<ul class="quickfield-field quickfield-radios %s">', $inline );

		foreach ( $settings['options'] as $radio_key => $radio_value ) {

			$checked = $radio_key === $value ? 'checked' : '';

			$output.=sprintf( '<li><label><input class="qf_value" %s %s type="radio" value="%s"/><span>%s</span></label></li>', $checked, implode( ' ', $attrs ), $radio_key, $radio_value );
		}

		$output.= '</ul>';
	}

	return $output;
}
