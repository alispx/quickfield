<?php
/**
 * Class Customize Field
 *
 * @class     Qf_Customize_Field
 * @package   Quick_Field/Classes
 * @category  Class
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */
if ( !class_exists( 'Qf_Customize_Field' ) ) {

	/**
	 * Qf_Customize_Field Class
	 */
	class Qf_Customize_Field {

		/**
		 * @access private
		 * @var array customize global
		 */
		private $wp_customize;

		/**
		 * @access private
		 * @var array Control types global
		 */
		private $control_types;

		/**
		 * Init
		 */
		public function __construct( $wp_customize, $args = array() ) {

			$this->wp_customize = $wp_customize;
			//Set types
			$this->set_control_types();

			//Add setting
			$this->add_setting( $args );

			//Add control
			$this->add_control( $args );
		}

		private function add_control( $args ) {

			if ( isset( $args['heading'] ) ) {
				$args['label'] = $args['heading'];
			}

			if ( isset( $args['options'] ) ) {
				$args['choices'] = $args['options'];
			}

			$defaults = array(
				'label' => '',
				'section' => '',
				'type' => '',
				'multiple' => 0,
				'priority' => '',
				'choices' => array(),
			);

			$args = wp_parse_args( $args, $defaults );

			$control_args = array(
				'label' => $args['label'],
				'section' => $args['section'],
				'type' => $args['type'],
				'multiple' => $args['multiple'],
				'choices' => $args['choices'],
				'priority' => $args['priority']
			);

			if ( $control_args['section'] instanceof Qf_Customize_Section ) {
				$control_args['section'] = $control_args['section']->id();
			}

			if ( $control_args['type'] == 'textfield' ) {
				$control_args['type'] = 'text';
			} elseif ( $control_args['type'] == 'image_picker' ) {
				$control_args['type'] = 'image';
			} else if ( $control_args['type'] == 'color_picker' ) {
				$control_args['type'] = 'color';
			}

			$control_name = isset( $args['name'] ) ? $args['name'] : '';

			// Get the name of the class we're going to use.
			$class_name = $this->control_class_name( $control_args );

			// Add the control.
			$this->wp_customize->add_control( new $class_name( $this->wp_customize, $control_name, $control_args ) );
		}

		private function add_setting( $args ) {

			if ( isset( $args['value'] ) ) {
				$args['default'] = $args['value'];
			}

			$defaults = array(
				'default' => '',
				'transport' => 'refresh',
				'capability' => 'edit_theme_options'
			);

			$args = wp_parse_args( $args, $defaults );

			$setting_args = array(
				'transport' => $args['transport'],
				'default' => $args['default'],
				'capability' => $args['capability']
			);

			$setting_name = isset( $args['name'] ) ? $args['name'] : '';

			$this->wp_customize->add_setting( $setting_name, $setting_args );
		}

		private function set_control_types() {

			global $quickfield_control_types;

			if ( empty( $quickfield_control_types ) ) {

				$quickfield_control_types = apply_filters( 'quickfield_control_types', array(
					'image' => 'WP_Customize_Image_Control',
					'cropped_image' => 'WP_Customize_Cropped_Image_Control',
					'upload' => 'WP_Customize_Upload_Control',
					'color' => 'WP_Customize_Color_Control',
					'qf_select' => 'Qf_Customize_Select_Control',
					'qf_multicheck' => 'Qf_Customize_Multicheck_Control',
					'qf_icon_picker' => 'Qf_Customize_Icon_Picker_Control'
						) );

				// Make sure the defined classes actually exist.
				foreach ( $quickfield_control_types as $key => $classname ) {

					if ( !class_exists( $classname ) ) {
						unset( $quickfield_control_types[$key] );
					}
				}
			}

			$this->control_types = $quickfield_control_types;
		}

		private function control_class_name( $args ) {

			$class_name = 'WP_Customize_Control';

			$type = $args['type'];

			if ( $type == 'checkbox' && absint( $args['multiple'] ) === 1 ) {
				$type = 'qf_multicheck';
			} else {
				if ( !in_array( $args['type'], array( 'image', 'cropped_image', 'upload', 'color' ) ) ) {
					$type = 'qf_' . $type;
				}
			}

			if ( array_key_exists( $type, $this->control_types ) ) {
				$class_name = $this->control_types[$type];
			}

			return $class_name;
		}

	}

}

if ( !class_exists( 'Qf_Customize_Section' ) ) {
	/**
	 * Qf_Customize_Section Class
	 */
	class Qf_Customize_Section {

		/**
		 * @access public
		 * @var array customize settings
		 */
		private $settings = array();

		/**
		 * @access private
		 * @var array customize global
		 */
		private $wp_customize;

		/**
		 * Section ID
		 * @var $id string
		 */
		private $id;

		/**
		 * Init
		 */
		public function __construct( $wp_customize, $args ) {

			if ( !empty( $args ) ) {

				$defaults = array(
					'panel' => '',
					'id' => '',
					'heading' => '',
					'description' => '',
					'priority' => 160,
					'capability' => 'edit_theme_options',
					'theme_supports' => '', // Rarely needed.
					'fields' => array()
				);


				$this->settings = wp_parse_args( $args, $defaults );

				$this->id = $this->settings['id'];

				$this->wp_customize = $wp_customize;

				$this->ouput();
			}
		}

		public function id() {
			return $this->id;
		}

		/**
		 * Section Output
		 */
		private function ouput() {

			$args = $this->settings;

			if ( isset( $args['heading'] ) ) {
				$args['title'] = $args['heading'];
				unset( $args['heading'] );
			}

			if ( $args['panel'] instanceof Qf_Customize_Panel ) {
				$args['panel'] = $args['panel']->id();
			}

			$this->wp_customize->add_section( $args['id'], $args );

			$this->add_fields( $args['fields'] );
		}

		public function add_fields( $fields = array() ) {
			foreach ( $fields as $field ) {
				$this->add_field( $field );
			}
		}

		public function add_field( $args = array() ) {
			$args['section'] = $this->id();
			new Qf_Customize_Field( $this->wp_customize, $args );
		}

	}

}

if ( !class_exists( 'Qf_Customize_Panel' ) ) {
	/**
	 * Qf_Customize_Panel Class
	 */
	class Qf_Customize_Panel {

		/**
		 * @access private
		 * @var array customize global
		 */
		private $wp_customize;

		/**
		 * @access private
		 * @var string
		 */
		private $id;

		/**
		 * Init
		 */
		public function __construct( $wp_customize, $args ) {

			if ( !empty( $args ) ) {

				$this->id = $args['id'];

				$this->wp_customize = $wp_customize;

				$this->add_panel( $args );
			}
		}

		public function id() {
			return $this->id;
		}

		/**
		 * @access private
		 * Add panel to customizer
		 */
		private function add_panel( $args ) {

			$defaults = array(
				'id' => '',
				'title' => '',
				'description' => '', // Include html tags such as <p>.
				'priority' => 160, // Mixed with top-level-section hierarchy.
			);

			$args = wp_parse_args( $args, $defaults );

			$this->wp_customize->add_panel( $args['id'], $args );
		}

		/**
		 * Add section
		 * 
		 * @param Qf_Customize_Section|array $section Section Class or array settings
		 */
		public function add_section( $args = array() ) {
			$args['panel'] = $this->id();
			new Qf_Customize_Section( $this->wp_customize, $args );
		}

		public function add_sections( $sections = array() ) {
			foreach ( $sections as $section ) {
				$this->add_section( $section );
			}
		}

	}

}	