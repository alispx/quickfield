<?php

/**
 * Sample Term Meta
 *
 * @package     Quickfield
 * @category    Sample
 * @author      vutuan.sw
 * @license     GPLv2 or later
 */

/**
 * Term Meta
 * Display in wp-admin/edit-tags.php?taxonomy=category
 */
function quickfield_example_category() {

	$fields = quickfield_example_fields();

	$repeater = array(
		'name' => 'quickfield_repeater',
		'type' => 'repeater',
		'heading' => __( 'Repeater', 'quickfield' ),
		'value' => '',
		'desc' => '',
		'fields' => $fields
	);

	$all = $fields;
	$all[] = $repeater;

	$term_args = array(
		'id' => 'quickfield_example_category',
		'pages' => array( 'category' ), //Display in category screen
		'heading' => __( 'Group label', 'quickfield' ),
		'fields' => array(
			//Group Default
			array(
				'name' => 'quickfield_textfield_g',
				'type' => 'textfield',
				'heading' => __( 'Text field:', 'quickfield' ),
				'value' => 'A default text',
				'desc' => __( 'A short description for Text Field', 'quickfield' ),
				'show_label' => true//Work on repeater field
			),
			array(
				'name' => 'quickfield_textarea_g',
				'type' => 'textarea',
				'heading' => __( 'Text Area:', 'quickfield' ),
				'value' => 'A default text',
				'desc' => __( 'A short description for Text Area', 'quickfield' ),
			),
			array(
				'name' => 'quickfield_select_g',
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
				'name' => 'quickfield_select_multiple_g',
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
				),
				'show_label' => true//Work on repeater field
			),
			array(
				'name' => 'quickfield_checkbox_g',
				'type' => 'checkbox',
				'heading' => __( 'Checkbox:', 'quickfield' ),
				'value' => 0,
				'desc' => __( 'A short description for single Checkbox', 'quickfield' )
			),
			array(
				'name' => 'quickfield_checkbox_multiple_g',
				'type' => 'checkbox',
				'multiple' => true,
				'heading' => __( 'Checkbox multiple:', 'quickfield' ),
				'value' => array(
					'donna', 'charles'
				),
				'inline' => 0,
				'options' => array(
					'donna' => __( 'Donna Delgado', 'quickfield' ),
					'eric' => __( 'Eric Austin', 'quickfield' ),
					'charles' => __( 'Charles Wheeler', 'quickfield' ),
					'anthony' => __( 'Anthony Perkins', 'quickfield' )
				),
				'desc' => __( 'A short description for Checkbox multiple', 'quickfield' )
			),
			array(
				'name' => 'quickfield_checkbox_radio_g',
				'type' => 'radio',
				'heading' => __( 'Radio multiple:', 'quickfield' ),
				'inline' => 1,
				'value' => 'eric',
				'options' => array(
					'donna' => __( 'Donna Delgado', 'quickfield' ),
					'eric' => __( 'Eric Austin', 'quickfield' ),
					'charles' => __( 'Charles Wheeler', 'quickfield' ),
					'anthony' => __( 'Anthony Perkins', 'quickfield' )
				),
				'description' => __( 'Checkbox multiple description', 'quickfield' ),
				'show_label' => true//Work on repeater field
			),
			array(
				'name' => 'quickfield_color_g',
				'type' => 'color_picker',
				'heading' => __( 'Color:', 'quickfield' ),
				'value' => '#cccccc',
				'desc' => __( 'A short description for Color Picker', 'quickfield' )
			),
			array(
				'name' => 'quickfield_image_g',
				'type' => 'image_picker',
				'multiple' => false,
				'heading' => __( 'Single Image:', 'quickfield' ),
				'desc' => __( 'A short description for Image Picker', 'quickfield' )
			),
			array(
				'name' => 'quickfield_multiple_image_g',
				'type' => 'image_picker',
				'multiple' => true,
				'heading' => __( 'Multi Image:', 'quickfield' ),
				'desc' => __( 'A short description for Image Picker with multiple is true', 'quickfield' )
			),
			array(
				'name' => 'quickfield_image_select_g',
				'type' => 'image_select',
				'inline' => 1, //Set 0 to display image vertical
				'heading' => __( 'Image Inline:', 'quickfield' ),
				'desc' => __( 'This is a demo for sidebar layout.', 'quickfield' ),
				'options' => array(
					'left' => QUICKFIELD_URL . 'sample/assets/sidebar-left.jpg',
					'none' => QUICKFIELD_URL . 'sample/assets/sidebar-none.jpg',
					'right' => QUICKFIELD_URL . 'sample/assets/sidebar-right.jpg',
				),
				'value' => 'right'//default
			),
			array(
				'name' => 'quickfield_image_select_vertical_g',
				'type' => 'image_select',
				'inline' => 0, //Vertical
				'heading' => __( 'Image Vertical:', 'quickfield' ),
				'desc' => __( 'This is a demo for vertical image options.', 'quickfield' ),
				'options' => array(
					'opt-1' => QUICKFIELD_URL . 'sample/assets/opt-1.jpg',
					'opt-2' => QUICKFIELD_URL . 'sample/assets/opt-2.jpg',
					'opt-3' => QUICKFIELD_URL . 'sample/assets/opt-3.jpg',
				),
				'value' => 'opt-1'//default
			),
			array(
				'name' => 'quickfield_icon_picker_g',
				'type' => 'icon_picker',
				'heading' => __( 'Icon Picker', 'quickfield' ),
				'desc' => __( 'A short description', 'quickfield' ),
			),
			array(
				'name' => 'quickfield_link_g',
				'type' => 'link',
				'heading' => __( 'Custom Link', 'quickfield' ),
				'desc' => __( 'We have a custom Link very friendly and easy to use.', 'quickfield' ),
				'value' => ''//default
			),
			array(
				'name' => 'quickfield_datetime_g',
				'type' => 'datetime',
				'heading' => __( 'Datetime', 'quickfield' ),
				'desc' => __( 'A cool datetime.', 'quickfield' ),
				'value' => ''//default
			),
			array(
				'name' => 'quickfield_map_g',
				'type' => 'map',
				'heading' => __( 'Search map location', 'quickfield' ),
				'desc' => __( 'Drag the pin to manually set listing coordinates. Now very easy to save a latlng and zoom settings from user. ', 'quickfield' ),
				'value' => ''//default
			),
			//Group
			array(
				'type' => 'repeater',
				'name' => 'repeater_group',
				'heading' => __( 'Repeater', 'quickfield' ),
				'group' => 'Repeater',
				'fields' => $fields
			)
		)//Array fields
	);
	new Qf_Term_Meta( $term_args );
}

add_action( 'admin_init', 'quickfield_example_category' );

