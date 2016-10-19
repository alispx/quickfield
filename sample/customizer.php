<?php

/**
 * Sample Customizer
 *
 * @package     Quickfield
 * @category    Sample
 * @author      vutuan.sw
 * @license     GPLv2 or later
 */

/**
 * Register customizer
 */
function quickfield_customize_register( $wp_customize ) {

	/**
	 * Init a Panel
	 */
	$panel = new Qf_Customize_Panel( $wp_customize, array(
		'id' => 'qf_panel',
		'title' => __( 'QUICKFIELD PANEL', 'quickfield' ),
		'description' => __( 'My Description', 'textdomain' ),
			) );

	/**
	 * Use Panel to add single section
	 */
	$panel->add_section( array(
		'id' => 'qf_section_1',
		'heading' => esc_attr__( 'SECTION I', 'quickfield' ),
		'fields' => array( //Fields in section
			array(
				'name' => 'qf_text_11',
				'type' => 'text',
				'heading' => 'Text Field',
			),
			array(
				'name' => 'qf_textarea_11',
				'type' => 'textarea',
				'heading' => __( 'Text Area', 'quickfield' ),
			),
		)
	) );

	/**
	 * Use Panel to add a list of sections
	 */
	$panel->add_sections( array(
		//Section 2
		array(
			'id' => 'qf_section_2',
			'heading' => esc_attr__( 'SECTION II', 'quickfield' ),
			'fields' => array(
				array(
					'name' => 'qf_text_21',
					'type' => 'text',
					'heading' => 'Text Field',
				),
				array(
					'name' => 'qf_textarea_22',
					'type' => 'textarea',
					'heading' => __( 'Text Area', 'quickfield' ),
				),
			)
		),
		//Section 3
		array(
			'id' => 'qf_section_3',
			'heading' => esc_attr__( 'SECTION III', 'quickfield' ),
			'fields' => array(
				array(
					'name' => 'qf_text_31',
					'type' => 'text',
					'heading' => 'Text Field',
				),
				array(
					'name' => 'qf_textarea_32',
					'type' => 'textarea',
					'heading' => __( 'Text Area', 'quickfield' ),
				),
			)
		)
	) );

	/**
	 * Init section and addto panel
	 */
	$section4 = new Qf_Customize_Section( $wp_customize, array(
		'id' => 'qf_section_4',
		'panel' => $panel, //Add panel
		'heading' => esc_attr__( 'SECTION IV', 'quickfield' ),
		'fields' => array(
			array(
				'name' => 'qf_text_41',
				'type' => 'text',
				'heading' => 'Text Field',
			),
		) )
	);

	/**
	 * Add fields to section
	 */
	$section4->add_fields( array(
		array(
			'name' => 'qf_textarea_42',
			'type' => 'textarea',
			'heading' => __( 'Text Area', 'quickfield' ),
		),
		array(
			'name' => 'qf_checkbox_41',
			'type' => 'checkbox',
			'value' => 1,
			'heading' => __( 'Single Checkbox', 'quickfield' )
		)
	) );

	/**
	 * Add single field to section
	 */
	$section4->add_field( array(
		'name' => 'qf_checkbox_42',
		'type' => 'checkbox',
		'heading' => __( 'Check list', 'quickfield' ),
		'multiple' => 1,
		'value' => 'eric',
		'options' => array(
			'donna' => __( 'Donna Delgado', 'quickfield' ),
			'eric' => __( 'Eric Austin', 'quickfield' ),
			'charles' => __( 'Charles Wheeler', 'quickfield' ),
			'anthony' => __( 'Anthony Perkins', 'quickfield' )
		),
	) );

	/**
	 * Init field and push to section
	 */
	new Qf_Customize_Field( $wp_customize, array(
		'name' => 'qf_radio',
		'type' => 'radio',
		'heading' => 'Radio',
		'transport' => 'refresh',
		'value' => '',
		'section' => $section4, //Push to section
		'options' => array(
			'' => 'None',
			1 => 'Hello World',
			2 => 'Hello Php',
			3 => 'Hello WordPress'
		) ) );


	/**
	 * Full Demo
	 */
	new Qf_Customize_Section( $wp_customize, array(
		'id' => 'qf_section_demo',
		'heading' => esc_attr__( 'QUICKFIELD CONTROLS', 'quickfield' ),
		'fields' => array(
			array(
				'name' => 'quickfield_textfield',
				'type' => 'textfield',
				'heading' => __( 'Text field:', 'quickfield' ),
				'value' => 'A default text',
			),
			array(
				'name' => 'quickfield_textarea',
				'type' => 'textarea',
				'heading' => __( 'Text Area:', 'quickfield' ),
				'value' => 'A default textarea',
			),
			array(
				'name' => 'quickfield_select',
				'type' => 'select',
				'heading' => __( 'Select:', 'quickfield' ),
				'value' => 'eric',
				'desc' => __( 'A short description for Select box', 'quickfield' ),
				'options' => array(
					'donna' => __( 'Donna Delgado', 'quickfield' ),
					'eric' => __( 'Eric Austin', 'quickfield' ),
					'charles' => __( 'Charles Wheeler', 'quickfield' ),
					'anthony' => __( 'Anthony Perkins', 'quickfield' )
				)
			),
			array(
				'name' => 'quickfield_select_multiple',
				'type' => 'select',
				'heading' => __( 'Select multiple:', 'quickfield' ),
				'desc' => __( 'A short description for Select Multiple', 'quickfield' ),
				'multiple' => true,
				'value' => array( 'eric', 'charles' ),
				'options' => array(
					'donna' => __( 'Donna Delgado', 'quickfield' ),
					'eric' => __( 'Eric Austin', 'quickfield' ),
					'charles' => __( 'Charles Wheeler', 'quickfield' ),
					'anthony' => __( 'Anthony Perkins', 'quickfield' )
				)
			),
			array(
				'name' => 'quickfield_checkbox',
				'type' => 'checkbox',
				'heading' => __( 'Checkbox', 'quickfield' ),
				'value' => 0,
			),
			array(
				'name' => 'quickfield_checkbox_multiple',
				'type' => 'checkbox',
				'multiple' => true,
				'heading' => __( 'Checkbox multiple:', 'quickfield' ),
				'value' => array(
					'donna', 'charles'
				),
				'options' => array(
					'donna' => __( 'Donna Delgado', 'quickfield' ),
					'eric' => __( 'Eric Austin', 'quickfield' ),
					'charles' => __( 'Charles Wheeler', 'quickfield' ),
					'anthony' => __( 'Anthony Perkins', 'quickfield' )
				),
			),
			array(
				'name' => 'quickfield_checkbox_radio',
				'type' => 'radio',
				'heading' => __( 'Radio multiple:', 'quickfield' ),
				'value' => 'eric',
				'options' => array(
					'donna' => __( 'Donna Delgado', 'quickfield' ),
					'eric' => __( 'Eric Austin', 'quickfield' ),
					'charles' => __( 'Charles Wheeler', 'quickfield' ),
					'anthony' => __( 'Anthony Perkins', 'quickfield' )
				),
			),
			array(
				'name' => 'quickfield_color',
				'type' => 'color_picker',
				'heading' => __( 'Color Picker:', 'quickfield' ),
				'value' => '#cccccc',
			),
			array(
				'name' => 'quickfield_icon_picker',
				'type' => 'icon_picker',
				'heading' => __( 'Icon Picker', 'quickfield' ),
			),
			array(
				'name' => 'quickfield_image',
				'type' => 'image_picker',
				'heading' => __( 'Single Image:', 'quickfield' ),
				'desc' => __( 'A short description for Image Picker', 'quickfield' )
			),
			array(
				'name' => 'quickfield_cropped_image',
				'type' => 'cropped_image',
				'heading' => __( 'Cropped Image:', 'quickfield' ),
			),
			array(
				'name' => 'quickfield_upload',
				'type' => 'upload',
				'heading' => __( 'Upload Field:', 'quickfield' ),
			),
			//Update later
		) )
	);
}

/**
 * Hook to Customize Register
 */
add_action( 'customize_register', 'quickfield_customize_register', 11 );
