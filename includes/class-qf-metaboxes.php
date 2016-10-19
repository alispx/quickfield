<?php

/**
 * Class Metaboxes
 *
 * @class     Qf_Metaboxes
 * @package   Quick_Field/Classes
 * @category  Class
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */
if ( !class_exists( 'Qf_Metaboxes' ) ) {

	/*
	 * Qf_Metaboxes Class
	 */

	class Qf_Metaboxes {

		/**
		 * @access private
		 * @var array meta boxes settings
		 */
		private $settings = array();

		/**
		 * @access private
		 * @var string Container of the field in markup HTML
		 */
		private $field_wrapper;

		/**
		 * @access private
		 * @var string Html of all output field
		 */
		private $output;

		/**
		 * Init
		 */
		public function __construct( $args = array() ) {
			if ( !empty( $args ) ) {

				$defaults = array(
					'id' => 'quickfield_metabox',
					'screens' => array( 'page', 'post' ),
					'title' => __( 'Quick Field Metabox', 'quickfield' ),
					'context' => 'advanced',
					'priority' => 'low',
					'fields' => array(
					)
				);

				$this->settings = wp_parse_args( $args, $defaults );

				add_action( 'add_meta_boxes', array( $this, 'register' ) );
				add_action( 'save_post', array( $this, 'save' ), 1, 2 );
			}
		}

		/**
		 * Register hook
		 * @return void
		 */
		public function register() {

			$this->field_wrapper = apply_filters( 'quickfield_metabox_field_wraper', '<div class="quickfield_form_row"><div class="col-label">%1$s</div><div class="col-field">%2$s</div></div>' );

			$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;

			$this->output = $this->pre_output( $post_id );

			foreach ( $this->settings['screens'] as $screen ) {
				add_meta_box( $this->settings['id'], $this->settings['title'], array( $this, 'output' ), $screen, $this->settings['context'], $this->settings['priority'], $this->settings['fields'] );
			}
		}

		/**
		 * Output callback
		 * @return void
		 */
		public function output( $post, $args ) {
			echo $this->output;
		}

		/**
		 * Process field output
		 * 
		 * @global array $quickfield_registered_fields
		 * @param int $post_id
		 * @return string Html
		 */
		public function pre_output( $post_id ) {

			global $quickfield_registered_fields;

			if ( empty( $quickfield_registered_fields ) ) {
				$quickfield_registered_fields = array();
			}

			$output = '';

			$metabox = $this->settings;

			$output.=sprintf( '<div class="quickfield-metabox quickfield-metabox_%s">', $metabox['id'] );

			$output.=sprintf( '<input type="hidden" name="%s_nonce" value="%s" />', $metabox['id'], wp_create_nonce( $metabox['id'] ) );

			foreach ( $metabox['fields'] as $field ) {
				/**
				 * Field value
				 */
				$value = get_post_meta( $post_id, $field['name'], false );

				if ( isset( $value[0] ) ) {
					$value = $value[0];
				} elseif ( empty( $value[0] ) ) {
					$value = isset( $field['value'] ) ? $field['value'] : '';
				} else {
					$value = '';
				}

				/**
				 * Add field type to global array
				 */
				$quickfield_registered_fields[] = $field['type'];

				/*
				 * Print field
				 */
				if ( function_exists( "quickfield_form_{$field['type']}" ) ) {

					$required = isset( $field['required'] ) && absint( $field['required'] ) ? '<span>*</span>' : '';

					$lable = !empty( $field['heading'] ) ? sprintf( '<label for="%1$s">%2$s %3$s</label>', $field['name'], $field['heading'], $required ) : '';

					$desc = !empty( $field['desc'] ) ? sprintf( '<p class="description">%s</p>', $field['desc'] ) : '';

					$output.= sprintf( $this->field_wrapper, $lable, call_user_func( "quickfield_form_{$field['type']}", $field, $value ) . $desc );
				} else if ( has_filter( "quickfield_form_{$field['type']}" ) ) {

					$field_output = apply_filters( "quickfield_form_{$field['type']}", '', $field, $value, $this->field_wrapper );
					$output.= sprintf( $this->field_wrapper, $lable, $field_output . $desc );
				}
			}

			$output.= '</div>';

			$quickfield_registered_fields = array_unique( $quickfield_registered_fields );

			return $output;
		}

		/**
		 * Save post meta
		 * @param int $post_id
		 * @param object|WP_Post $post
		 */
		public function save( $post_id, $post ) {

			$metabox = $this->settings;

			/* don't save if $_POST is empty */
			if ( empty( $_POST ) )
				return $post_id;

			/* don't save during autosave */
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return $post_id;

			/* verify nonce */
			if ( !isset( $_POST[$metabox['id'] . '_nonce'] ) || !wp_verify_nonce( $_POST[$metabox['id'] . '_nonce'], $metabox['id'] ) )
				return $post_id;

			/* check permissions */
			if ( isset( $_POST['post_type'] ) && 'page' == sanitize_text_field( $_POST['post_type']) ) {
				if ( !current_user_can( 'edit_page', $post_id ) )
					return $post_id;
			} else {
				if ( !current_user_can( 'edit_post', $post_id ) )
					return $post_id;
			}

			foreach ( $metabox['fields'] as $field ) {

				if ( !isset( $field['name'] ) ) {
					continue;
				}

				$value = '';

				if ( isset( $_POST[$field['name']] ) ) {

					if ( isset( $field['multiple'] ) && $field['multiple'] ) {

						$value = maybe_unserialize( $_POST[$field['name']] );
					} elseif ( $field['type'] == 'checkbox' ) {

						$value = !empty( $_POST[$field['name']] ) ? 1 : 0;
					} elseif ( $field['type'] == 'link' ) {

						$value = strip_tags( $_POST[$field['name']] );
					} elseif ( $field['type'] == 'textarea' ) {

						$value = wp_kses( trim( wp_unslash( $_POST[$field['name']] ) ), wp_kses_allowed_html( 'post' ) );
					} else {

						$value = sanitize_text_field( $_POST[$field['name']] );
					}

					/**
					 * Allow third party filter value
					 */
					$value = apply_filters( "quickfield_sanitize_field_{$field['type']}", $value, $_POST[$field['name']] );

					update_post_meta( $post_id, $field['name'], $value );
				}
			}

			do_action( sprintf( 'quickfield_%s_updated', $metabox['id'] ), $post_id, $post );
		}

	}

}