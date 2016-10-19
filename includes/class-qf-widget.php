<?php

/**
 * Abstract Widget Class
 *
 * @class     Qf_Widget
 * @extends	  WP_Widget
 * @package   Quick_Field/Classes
 * @category  Abstract Class
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Qf_Widget abstract class
 */
abstract class Qf_Widget extends WP_Widget {

	/**
	 * CSS class.
	 *
	 * @var string
	 */
	public $widget_cssclass;

	/**
	 * Widget description.
	 *
	 * @var string
	 */
	public $widget_description;

	/**
	 * Widget ID.
	 *
	 * @var string
	 */
	public $widget_id;

	/**
	 * Widget name.
	 *
	 * @var string
	 */
	public $widget_name;

	/**
	 * Settings.
	 *
	 * @var array
	 */
	public $fields;

	/**
	 * @access private
	 * @var string Container of the field in markup HTML
	 */
	private $field_wrapper;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$widget_ops = array(
			'classname' => $this->widget_cssclass,
			'description' => $this->widget_description,
			'customize_selective_refresh' => true
		);

		parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	/**
	 * Get cached widget.
	 *
	 * @param  array $args
	 * @return bool true if the widget is cached otherwise false
	 */
	public function get_cached_widget( $args ) {

		$cache = wp_cache_get( apply_filters( 'quickfield_cached_widget_id', $this->widget_id ), 'widget' );

		if ( !is_array( $cache ) ) {
			$cache = array();
		}

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return true;
		}

		return false;
	}

	/**
	 * Cache the widget.
	 *
	 * @param  array $args
	 * @param  string $content
	 * @return string the content that was cached
	 */
	public function cache_widget( $args, $content ) {
		wp_cache_set( apply_filters( 'quickfield_cached_widget_id', $this->widget_id ), array( $args['widget_id'] => $content ), 'widget' );

		return $content;
	}

	/**
	 * Flush the cache.
	 */
	public function flush_widget_cache() {
		wp_cache_delete( apply_filters( 'quickfield_cached_widget_id', $this->widget_id ), 'widget' );
	}

	/**
	 * Output the html at the start of a widget.
	 *
	 * @param  array $args
	 * @return string
	 */
	public function widget_start( $args, $instance ) {
		echo $args['before_widget'];

		if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
	}

	/**
	 * Output the html at the end of a widget.
	 *
	 * @param  array $args
	 * @return string
	 */
	public function widget_end( $args ) {
		echo $args['after_widget'];
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @see    WP_Widget->update
	 * @param  array $new_instance
	 * @param  array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		if ( empty( $this->fields ) ) {
			return $instance;
		}

		// Loop fields and get values to save.
		foreach ( $this->fields as $field ) {

			if ( !isset( $field['type'] ) ) {
				continue;
			}

			$value = '';

			if ( isset( $field['multiple'] ) && $field['multiple'] ) {

				$value = maybe_unserialize( $new_instance[$field['name']] );
			} elseif ( $field['type'] == 'checkbox' ) {

				$value = !empty( $new_instance[$field['name']] ) ? 1 : 0;
			} elseif ( $field['type'] == 'link' ) {

				$value = strip_tags( $new_instance[$field['name']] );
			} elseif ( $field['type'] == 'textarea' ) {

				$value = wp_kses( trim( wp_unslash( $new_instance[$field['name']] ) ), wp_kses_allowed_html( 'post' ) );
			} else {

				$value = sanitize_text_field( $new_instance[$field['name']] );
			}

			/**
			 * Allow third party filter value
			 */
			$value = apply_filters( "quickfield_sanitize_field_{$field['type']}", $value, $new_instance[$field['name']] );

			/**
			 * Sanitize the value of a field.
			 */
			$instance[$field['name']] = $value;
		}

		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Outputs the fields update form.
	 *
	 * @see   WP_Widget->form
	 * @param array $instance
	 */
	public function form( $instance ) {
		echo $this->pre_output( $instance );
	}

	/**
	 * Process field output
	 * 
	 * @global array $quickfield_registered_fields
	 * @param int $post_id
	 * @return string Html
	 */
	public function pre_output( $instance ) {

		if ( empty( $this->fields ) ) {
			return;
		}

		global $quickfield_registered_fields;

		if ( empty( $quickfield_registered_fields ) ) {
			$quickfield_registered_fields = array();
		}

		$this->field_wrapper = apply_filters( 'quickfield_widget_field_wraper', '<div class="quickfield_widget_row"><div class="col-label">%1$s</div><div class="col-field">%2$s</div></div>' );

		$output = '';

		foreach ( $this->fields as $field ) {
			/**
			 * @var $default_value Default Value
			 */
			$default_value = isset( $field['value'] ) ? $field['value'] : '';

			/**
			 * @var $default_value Default Value
			 * If you used to use `std` as a `default value`, please use it :)
			 */
			$default_value = isset( $field['std'] ) ? $field['std'] : $default_value;

			/**
			 * @var $value Field value
			 */
			$value = isset( $instance[$field['name']] ) ? $instance[$field['name']] : $default_value;

			$field['id'] = $this->get_field_id( $field['name'] );

			$field['name'] = $this->get_field_name( $field['name'] );

			/**
			 * Add field type to global array
			 */
			$quickfield_registered_fields[] = $field['type'];

			/*
			 * Print field
			 */
			if ( function_exists( "quickfield_form_{$field['type']}" ) ) {

				$required = isset( $field['required'] ) && absint( $field['required'] ) ? '<span>*</span>' : '';

				$lable = !empty( $field['heading'] ) ? sprintf( '<label for="%1$s">%2$s %3$s</label>', $field['id'], $field['heading'], $required ) : '';

				$desc = !empty( $field['desc'] ) ? sprintf( '<p class="description">%s</p>', $field['desc'] ) : '';

				$output.= sprintf( $this->field_wrapper, $lable, call_user_func( "quickfield_form_{$field['type']}", $field, $value ) . $desc );
			} else if ( has_filter( "quickfield_form_{$field['type']}" ) ) {

				$field_output = apply_filters( "quickfield_form_{$field['type']}", '', $field, $value, $this->field_wrapper );
				$output.= sprintf( $this->field_wrapper, $lable, $field_output . $desc );
			}
		}

		$quickfield_registered_fields = array_unique( $quickfield_registered_fields );

		return $output;
	}

}
