<?php

/**
 * Class Term Meta
 *
 * @class     Qf_Metaboxes
 * @package   Quick_Field/Classes
 * @category  Class
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */
if ( !class_exists( 'Qf_Term_Meta' ) ) {

	/**
	 * Qf_Term_Meta Class
	 */
	class Qf_Term_Meta {

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
		 * @access private
		 * @var array Group fields
		 */
		private $group_fields = array();
		private $field_add_wrapper;
		private $field_edit_wrapper;

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

				$this->register();
			}
		}

		/**
		 * Register hook
		 * @return void
		 */
		public function register() {

			$term_id = isset( $_GET['tag_ID'] ) ? absint( $_GET['tag_ID'] ) : 0;

			$this->field_edit_wrapper = apply_filters( 'quickfield_term_form_edit_field_wrapper', '<tr class="form-field term-group-wrap quickfield_form_row"><th scope="row">%1$s</th><td>%2$s</td></tr>' );
			$this->field_add_wrapper = apply_filters( 'quickfield_term_form_add_field_wrapper', '<div class="quickfield_form_row"><div class="col-label">%1$s</div><div class="col-field">%2$s</div></div>' );

			$this->output = $this->pre_output( $term_id );

			foreach ( $this->settings['pages'] as $page ) {

				add_action( $page . '_add_form_fields', array( $this, 'output_form_add' ), 10, 2 );
				add_action( $page . '_edit_form_fields', array( $this, 'output_form_edit' ), 10, 2 );

				add_action( 'created_' . $page, array( $this, 'add_term_meta' ), 10, 2 );
				add_action( 'edited_' . $page, array( $this, 'edit_term_meta' ), 10, 2 );
			}
		}

		/**
		 * Screen form add
		 */
		public function output_form_add( $taxonomy ) {
			echo $this->output;
		}

		/**
		 * Screen form edit
		 */
		public function output_form_edit( $term, $taxonomy ) {
			echo $this->output;
		}

		/**
		 * Save value in form add term meta
		 */
		public function add_term_meta( $term_id, $tt_id ) {
			$this->update_term_meta( $term_id, $tt_id, 'add' );
		}

		/**
		 * Save value in form edit term meta
		 */
		public function edit_term_meta( $term_id, $tt_id ) {
			$this->update_term_meta( $term_id, $tt_id, 'edit' );
		}

		/**
		 * Process field output
		 * 
		 * @global array $quickfield_registered_fields
		 * @param int $term_id
		 * @return string Html
		 */
		private function pre_output( $term_id = 0 ) {

			global $quickfield_registered_fields;

			if ( empty( $quickfield_registered_fields ) ) {
				$quickfield_registered_fields = array();
			}

			$output = '';

			$metabox = $this->settings;

			$output.= sprintf( '<input type="hidden" name="%s_nonce" value="%s" />', $metabox['id'], wp_create_nonce( $metabox['id'] ) );

			foreach ( $metabox['fields'] as $field ) {
				/**
				 * Field value
				 */
				$value = '';

				if ( $term_id ) {
					$value = get_term_meta( $term_id, $field['name'], false );
				} else {
					$value = null;
				}

				if ( isset( $value[0] ) ) {
					$value = $value[0];
				} elseif ( empty( $value[0] ) ) {
					$value = isset( $field['value'] ) ? $field['value'] : '';
				}

				$field['value'] = $value;

				/**
				 * Add field type to global array
				 */
				$quickfield_registered_fields[] = $field['type'];

				/**
				 * Add field to group
				 * @since 1.0.2
				 */
				$group = !empty( $field['group'] ) ? $field['group'] : '';

				if ( empty( $this->group_fields[$group] ) ) {
					$this->group_fields[$group] = array();
				}
				$this->group_fields[$group][] = $field;
			}


			if ( count( $this->group_fields ) == 1 && !key( $this->group_fields ) ) {

				if ( $term_id ) {
					$this->field_wrapper = $this->field_edit_wrapper;
				} else {
					$this->field_wrapper = $this->field_add_wrapper;
				}

				$fields = $this->group_fields[''];
				foreach ( $fields as $key => $field ) {
					$output.=$this->field_render( $field );
				}
			} else {

				$nav = '';
				$content = '';
				$index = 0;

				//Use form add for all field in group
				$this->field_wrapper = $this->field_add_wrapper;

				foreach ( $this->group_fields as $name => $fields ) {
					$name = empty( $name ) ? __( 'General', 'quickfield' ) : $name;
					$index ++;
					$active = $index == 1 ? 'active' : '';
					$id = $this->settings['id'] . '-group_' . $index;
					$nav.= sprintf( '<li><a href="#%s" class="%s">%s</a></li>', $id, $active, $name );

					$field_html = '';

					foreach ( $fields as $key => $field ) {
						$field_html.= $this->field_render( $field );
					}

					$content.= sprintf( '<div id="%s" class="group_item %s">%s</div>', $id, $active, $field_html );
				}

				$output.='<div class="quickfield_group">';
				$output.='<ul class="group_nav">' . $nav . '</ul>';
				$output.='<div class="group_panel">' . $content . '</div>';
				$output.='</div>';

				if ( $term_id ) {
					$this->field_wrapper = $this->field_edit_wrapper;
				} else {
					$this->field_wrapper = $this->field_add_wrapper;
				}

				$group_label = !empty( $metabox['heading'] ) ? sprintf( '<label>%s</label>', $metabox['heading'] ) : '';

				$output = sprintf( $this->field_wrapper, $group_label, $output );
			}

			$quickfield_registered_fields = array_unique( $quickfield_registered_fields );

			return $output;
		}

		/**
		 * Process field
		 * @access private
		 * @return string Field Html
		 */
		private function field_render( $field ) {

			$field_output = '';

			$field['id'] = $field['name'];

			$required = isset( $field['required'] ) && absint( $field['required'] ) ? '<span>*</span>' : '';

			$lable = !empty( $field['heading'] ) ? sprintf( '<label for="%1$s">%2$s %3$s</label>', $field['name'], $field['heading'], $required ) : '';

			$desc = !empty( $field['desc'] ) ? sprintf( '<p class="desc description">%s</p>', $field['desc'] ) : '';

			if ( function_exists( "quickfield_form_{$field['type']}" ) ) {
				$field_output = sprintf( $this->field_wrapper, $lable, call_user_func( "quickfield_form_{$field['type']}", $field, $field['value'] ) . $desc );
			} else if ( has_filter( "quickfield_form_{$field['type']}" ) ) {

				$field_output = apply_filters( "quickfield_form_{$field['type']}", '', $field );
				$field_output = sprintf( $this->field_wrapper, $lable, $field_output . $desc );
			}

			return $field_output;
		}

		/**
		 * Save term meta
		 * @access private
		 * @return void
		 */
		private function update_term_meta( $term_id, $tt_id, $cmd = 'add' ) {

			/* don't save if $_POST is empty */
			if ( empty( $_POST ) )
				return $term_id;

			/* verify nonce */
			if ( !isset( $_POST[$this->settings['id'] . '_nonce'] ) || !wp_verify_nonce( $_POST[$this->settings['id'] . '_nonce'], $this->settings['id'] ) )
				return $term_id;

			$fields = $this->settings['fields'];

			foreach ( $fields as $field ) {

				if ( !isset( $field['name'] ) ) {
					continue;
				}

				$value = '';

				if ( isset( $_POST[$field['name']] ) ) {


					$input_value = $_POST[$field['name']];

					if ( isset( $field['multiple'] ) && $field['multiple'] ) {
						$value = maybe_unserialize( $input_value );
					} elseif ( $field['type'] == 'checkbox' ) {

						$value = !empty( $input_value ) ? 1 : 0;
					} elseif ( $field['type'] == 'link' ) {

						$value = strip_tags( $input_value );
					} elseif ( $field['type'] == 'textarea' ) {

						$value = wp_kses( trim( wp_unslash( $input_value ) ), wp_kses_allowed_html( 'post' ) );
					} elseif ( $field['type'] == 'repeater' && !empty( $input_value ) ) {
						$value = json_encode( $input_value, JSON_UNESCAPED_UNICODE );
					} else {
						$value = sanitize_text_field( $input_value );
					}

					/**
					 * Allow third party filter value
					 */
					$value = apply_filters( "quickfield_sanitize_field_{$field['type']}", $value, $_POST[$field['name']] );

					if ( $cmd === 'add' ) {
						add_term_meta( $term_id, $field['name'], $value, true );
					} else {
						update_term_meta( $term_id, $field['name'], $value );
					}
				}
			}
		}

	}

}