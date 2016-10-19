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

	$value = htmlspecialchars( $value );

	$id = isset( $settings['id'] ) ? $settings['id'] : $settings['name'];

	return sprintf( '<input type="text" name="%1$s" id="%2$s" class="quickfield-textfield widefat %2$s %3$s" value="%4$s"/>', $settings['name'], $id, $settings['type'], $value );
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

	$id = isset( $settings['id'] ) ? $settings['id'] : $settings['name'];

	return sprintf( '<textarea type="text" name="%1$s"  id="%2$s" class="quickfield-textarea widefat %2$s %3$s">%4$s</textarea>', $settings['name'], $id, $settings['type'], esc_textarea( $value ) );
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

	$name = isset( $settings['name'] ) ? $settings['name'] : '';

	$id = isset( $settings['id'] ) ? $settings['id'] : $name;

	$attr = isset( $settings['attr'] ) ? $settings['attr'] : '';

	$multiple = isset( $settings['multiple'] ) && $settings['multiple'] ? 1 : 0;

	$output = '';

	if ( $multiple ) {

		if ( is_array( $settings['options'] ) ) {

			$inline = isset( $settings['display_inline'] ) && absint( $settings['display_inline'] ) ? 'inline' : '';

			$output.= sprintf( '<ul class="quickfield-checkboxes %s">', $inline );

			if ( !empty( $name ) ) {
				$name.='[]';
			}

			foreach ( $settings['options'] as $checkbox_key => $checkbox_value ) {

				if ( is_array( $value ) ) {
					$checked = in_array( $checkbox_key, $value ) ? 'checked' : '';
				} else {
					$checked = $checkbox_key === $value ? 'checked' : '';
				}

				$output.=sprintf( '<li><label><input %s type="checkbox" name="%s" value="%s"/><span>%s</span></label></li>', $checked, $name, $checkbox_key, $checkbox_value );
			}

			$output.= '</ul>';
		}
	} else {

		$checked = absint( $value ) ? 'checked' : '';

		$output.=sprintf( '<input %3$s type="checkbox" value="1" name="%1$s" id="%5$s" class="quickfield-checkbox %1$s %2$s" %4$s/>', $name, $settings['type'], $checked, $attr, $id );
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

	$name = isset( $settings['name'] ) ? $settings['name'] : '';

	$id = isset( $settings['id'] ) ? $settings['id'] : $name;

	$attr = isset( $settings['attr'] ) ? $settings['attr'] : '';

	if ( !empty( $multiple ) && !empty( $name ) ) {
		$name.='[]';
	}

	$output = sprintf( '<select %1$s id="%2$s" name="%3$s" %4$s class="quickfield-select">', $attr, $id, $name, $multiple );

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
	$output = '';

	if ( is_array( $settings['options'] ) ) {

		$inline = isset( $settings['display_inline'] ) && absint( $settings['display_inline'] ) ? 'inline' : '';

		$output.= sprintf( '<ul class="quickfield-radios %s">', $inline );

		foreach ( $settings['options'] as $radio_key => $radio_value ) {

			$checked = $radio_key === $value ? 'checked' : '';

			$output.=sprintf( '<li><label><input %s type="radio" name="%s" value="%s"/><span>%s</span></label></li>', $checked, $settings['name'], $radio_key, $radio_value );
		}

		$output.= '</ul>';
	}

	return $output;
}
