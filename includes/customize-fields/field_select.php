<?php
/**
 * Class Select Control
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
	 * Qf_Customize_Select_Control Class
	 */
	class Qf_Customize_Select_Control extends WP_Customize_Control {

		public $type = 'qf_select';

		/**
		 * Maximum number of options the user will be able to select.
		 * Set to 1 for single-select.
		 *
		 * @access public
		 * @var int
		 */
		public $multiple = 0;
		
		public function render_content() {
			
			echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';
			
			$args = array(
				'options' => $this->choices,
				'multiple' => $this->multiple,
				'attr' => $this->get_link(),
			);
			
			echo quickfield_form_select( $args );
		}

	}

endif;