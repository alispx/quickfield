<?php

/**
 * Class Multicheck Control
 *
 * @class     Qf_Customize_Multicheck_Control
 * @package   Quick_Field/Customize_Field
 * @category  Class
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ):

	/**
	 * Qf_Customize_Multicheck_Control Class
	 */
	class Qf_Customize_Multicheck_Control extends WP_Customize_Control {

		public $type = 'qf_multicheck';

		/**
		 * Maximum number of options the user will be able to select.
		 * Set to 1 for single-select.
		 *
		 * @access public
		 * @var int
		 */
		public $multiple = 1;

		/**
		 * Render control
		 * @access public
		 */
		public function render_content() {

			echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';

			$args = array(
				'options' => $this->choices,
				'multiple' => $this->multiple,
				'type' => $this->type
			);
			$value = '';
			
			if ( is_string( $this->value() ) && !empty( $this->value() ) && $this->multiple ) {
				$value = explode( ',', $this->value() );
			} else if ( is_array( $this->value() ) ) {
				$value = $this->value();
			}

			$input_value = is_array( $this->value() ) ? implode( ',', $this->value() ) : $this->value();

			echo quickfield_form_checkbox( $args, $value );

			printf( '<input class="qf-holder_value" type="hidden" value="%s" %s/>', $input_value, $this->get_link() );
		}

	}

	

endif;