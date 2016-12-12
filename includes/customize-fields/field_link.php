<?php

/**
 * Class Link Control
 *
 * @class     Qf_Customize_Link_Control
 * @package   Quick_Field/Customize_Field
 * @category  Class
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ):

	/**
	 * Qf_Customize_Link_Control Class
	 */
	class Qf_Customize_Link_Control extends WP_Customize_Control {
	
		/**
		 * @var string Field type
		 */
		public $type = 'qf_link';

		/**
		 * Render control
		 * @access public
		 */
		public function render_content() {

			echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';

			$args = array(
				'type' => $this->type,
				'customize_link' => $this->get_link()
			);

			echo quickfield_form_link( $args, $this->value() );
		}

	}
endif;