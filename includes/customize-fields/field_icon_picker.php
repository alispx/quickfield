<?php
/**
 * Class Icon Picker Control
 *
 * @class     Qf_Customize_Icon_Picker_Control
 * @package   Quick_Field/Customize_Field
 * @category  Class
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ):
	
	/**
	 * Qf_Customize_Icon_Picker_Control Class
	 */
	class Qf_Customize_Icon_Picker_Control extends WP_Customize_Control {

		public $type = 'qf_icon_picker';
		
		/**
		 * Render control
		 * @access public
		 */
		public function render_content() {

			echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';
			
			$args = array(
				'attr' => $this->get_link(),
			);
			
			echo quickfield_form_icon_picker( $args, $this->value() );
			
		}

	}

	
endif;