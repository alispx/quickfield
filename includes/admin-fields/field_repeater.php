<?php
/**
 * Repeater
 * 
 * @package   Quick_Field/Corefields
 * @category  Functions
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.1
 */
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Field Repeater
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.1
 * @return string - html string.
 */
function quickfield_form_repeater( $settings, $value ) {
		ob_start();
	
	$args = wp_parse_args( $settings, array(
		'id' => '',
		'name' => '',
		'fields' => array()
			) );

	$value_default = array();
	$value_default_item = array();

	if ( count( $args['fields'] ) ) {
		foreach ( $args['fields'] as $field ) {
			$_val = '';
			if ( isset( $field['value'] ) ) {
				if ( is_array( $field['value'] ) ) {
					$_val = implode( ',', $field['value'] );
				} else {
					$_val = isset( $field['value'] ) ? $field['value'] : '';
				}
			}

			$value_default_item[$field['name']] = $_val;
		}


		$value_default[] = $value_default_item;
		$value_default = json_encode( $value_default );

		if ( empty( $value ) ) {
			$value = $value_default;
		}
	}

	$field_wrapper = '<div class="repeater_form_row %3$s" data-type="%4$s"><div class="repeater-col-label">%1$s</div><div class="repeater-col-field">%2$s</div></div>';


	/**
	 * Support widget
	 */
	$id = isset( $args['_id'] ) ? $args['_id'] : $args['id'];
	$name = isset( $args['_name'] ) ? $args['_name'] : $args['name'];
	?>
	<div class="quickfield-field quickfield-repeater" id="quickfield-repeater-<?php echo esc_attr( $id ) ?>" data-value="<?php echo esc_attr( $value ) ?>">
		<?php
		/**
		 * Support Customizer and Widget
		 */
		if ( !empty( $settings['customize_link'] ) || !empty( $settings['widget_support'] ) ) {
			$hidden_name = $settings['customize_link'];
			if ( $settings['widget_support'] ) {
				$hidden_name = sprintf( 'name="%s"', $args['name'] );
			}
			printf( '<input class="qf_value" type="hidden" value="%s" %s/>', esc_attr( $value ), $hidden_name );
		}
		?>
		<div class="qf-repeater-list" data-repeater-list="<?php echo esc_attr( $name ) ?>">
			<div data-repeater-item="">

				<div class="qf-widget">	

					<div class="qf-widget-top">
						<div class="qf-widget-title-action">
							<a class="cmd" data-repeater-edit title="<?php echo esc_attr__( 'Edit', 'quickfield' ) ?>"><i class="fa fa-pencil"></i></a>
							<a class="cmd" data-repeater-delete title="<?php echo esc_attr__( 'Remove', 'quickfield' ) ?>"><i class="fa fa-trash-o"></i></a>
						</div>
						<div class="qf-widget-title ui-sortable-handle">
							<h4><i class="fa fa-ellipsis-v"></i><span class="js-heading-text"></span></h4>
						</div>
					</div>

					<div class="qf-widget-inside">
						<?php
						if ( count( $args['fields'] ) ) {

							foreach ( $args['fields'] as $field ) {

								if ( function_exists( "quickfield_form_{$field['type']}" ) ) {

									$lable = !empty( $field['heading'] ) ? sprintf( '<label>%s</label>', $field['heading'] ) : '';

									$desc = !empty( $field['desc'] ) ? sprintf( '<p class="repeater-field-description">%s</p>', $field['desc'] ) : '';

									$show_label = !empty( $field['show_label'] ) ? 'show_label' : '';

									$row_field_class = sprintf( 'repeater_field_%s type_%s %s ', $field['name'], $field['type'], $show_label );

									$field['el_class'] = 'child-field';

									printf( $field_wrapper, $lable, call_user_func( "quickfield_form_{$field['type']}", $field, '' ) . $desc, $row_field_class, $field['type'] );
								}
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<button class="btn-add" data-repeater-create type="button">
			<i class="qf-icon-add"></i>
		</button>
	</div>
	<?php
	return ob_get_clean();
}
