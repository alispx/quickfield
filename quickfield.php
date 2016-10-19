<?php
/*
  Plugin Name: Quickfield
  Plugin URI: http://wordpress.org/plugins/quickfield/
  Description: The toolkit for theme developers easy to build Customizer, Metabox, Term Meta, Widgets
  Author: vutuan.sw
  Version: 1.0.0
  Author URI: https://vutuansw.wordpress.com/

  License: GPLv2 or later
  License URI: URI: http://www.gnu.org/licenses/gpl-2.0.html
  Requires at least: 4.1
  Tested up to: 4.6
 */

class Quick_Field {

	/**
	 * Quick_Field version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * The single instance of the class.
	 *
	 * @var Quick_Field
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Quick_Field Instance.
	 *
	 * Ensures only one instance of Quick_Field is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see quickfield()
	 * @return Quick_Field - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {

		$this->defined();
		$this->admin_fields();
		$this->includes();
		$this->hooks();
	}

	public function hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'customize_register', array( $this, 'customize_fields' ), 10 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_scripts' ) );
	}

	public function customize_fields() {
		include QUICKFIELD_DIR . 'includes/customize-fields/field_select.php';
		include QUICKFIELD_DIR . 'includes/customize-fields/field_multicheck.php';
		include QUICKFIELD_DIR . 'includes/customize-fields/field_icon_picker.php';
	}

	public function admin_fields() {
		include QUICKFIELD_DIR . 'includes/admin-fields/field_default.php';
		include QUICKFIELD_DIR . 'includes/admin-fields/field_color_picker.php';
		include QUICKFIELD_DIR . 'includes/admin-fields/field_image_picker.php';
		include QUICKFIELD_DIR . 'includes/admin-fields/field_image_background.php';
		include QUICKFIELD_DIR . 'includes/admin-fields/field_image_select.php';
		include QUICKFIELD_DIR . 'includes/admin-fields/field_icon_picker.php';
		include QUICKFIELD_DIR . 'includes/admin-fields/field_link.php';
		include QUICKFIELD_DIR . 'includes/admin-fields/field_map.php';
	}

	public function includes() {
		include QUICKFIELD_DIR . 'includes/class-qf-metaboxes.php';
		include QUICKFIELD_DIR . 'includes/class-qf-customizer.php';
		include QUICKFIELD_DIR . 'includes/class-qf-term-meta.php';
		include QUICKFIELD_DIR . 'includes/class-qf-widget.php';

		include QUICKFIELD_DIR . 'includes/qf-functions.php';
	}

	public function defined() {
		define( 'QUICKFIELD_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'QUICKFIELD_VERSION', $this->version );
		define( 'QUICKFIELD_DIR', plugin_dir_path( __FILE__ ) );
		define( 'QUICKFIELD_URL', plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Load Localisation files.
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'quickfield', false, QUICKFIELD_DIR . 'languages/' );
	}

	/**
	 * Enqueue admin scripts
	 * @return void
	 */
	public function admin_scripts( $hook_suffix ) {

		global $quickfield_registered_fields;

		if ( !empty( $quickfield_registered_fields ) ) {

			wp_enqueue_style( 'font-awesome', QUICKFIELD_URL . '/assets/css/font-awesome.min.css', null, '4.6.3' );
			wp_enqueue_style( 'quickfield-admin', QUICKFIELD_URL . '/assets/css/admin.css', null, QUICKFIELD_VERSION );
			wp_enqueue_script( 'quickfield-admin', QUICKFIELD_URL . '/assets/js/admin_fields.min.js', array( 'jquery' ), QUICKFIELD_VERSION );

			foreach ( $quickfield_registered_fields as $type ) {
				switch ( $type ) {
					case 'color_picker':
						wp_enqueue_script( 'wp-color-picker' );
						wp_enqueue_style( 'wp-color-picker' );
						break;
					case 'image_picker';
					case 'image_background';
						wp_enqueue_media();
						wp_enqueue_script( 'jquery-ui' );
						break;
					case 'map':
						$gmap_key = sanitize_text_field( apply_filters( 'quickfield_gmap_key', 'AIzaSyCsBPWZ52X6EvpYCPuSWdqiIrazdJodFLk' ) );
						wp_enqueue_script( 'google-map-v-3', "//maps.googleapis.com/maps/api/js?libraries=places&key={$gmap_key}", array( 'jquery' ), null, true );
						wp_enqueue_script( 'geocomplete', QUICKFIELD_URL . '/assets/js/jquery.geocomplete.min.js', null, QUICKFIELD_VERSION );
						break;
					case 'icon_picker':
						wp_enqueue_script( 'font-iconpicker', QUICKFIELD_URL . '/assets/js/jquery.fonticonpicker.min.js', array( 'jquery' ), QUICKFIELD_VERSION );
						wp_enqueue_style( 'font-iconpicker', QUICKFIELD_URL . '/assets/css/jquery.fonticonpicker.css', null, QUICKFIELD_VERSION );
						break;
					case 'link':
						$screens = apply_filters( 'quickfield_link_on_screens', array( 'post.php', 'post-new.php' ) );
						if ( !in_array( $hook_suffix, $screens ) ) {
							wp_enqueue_style( 'editor-buttons' );
							wp_enqueue_script( 'wplink' );
							add_action( 'in_admin_header', 'quickfield_link_editor_hidden' );
						}
						break;
					default :
						do_action( 'quickfield_admin_scripts', $type );
						break;
				}
			}
		}
	}

	/**
	 * Binds the JS listener to make Customizer control
	 *
	 * @since 1.0.0
	 */
	function customize_scripts() {
		wp_enqueue_script( 'color-scheme-control', QUICKFIELD_URL . '/assets/js/customize-fields.min.js', array( 'customize-controls' ), QUICKFIELD_VERSION, true );
	}

}

/**
 * Main instance of Quick_Field.
 *
 * Returns the main instance of Quick_Field to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Quick_Field
 */
function quickfield() {
	return Quick_Field::instance();
}

// Global for backwards compatibility.
$GLOBALS['quickfield'] = quickfield();


/**
 * Sample
 * 
 * 1. Widget Demo
 * 2. Term Meta Demo
 * 3. Post, Page Metabox Demo
 * 4. Customizer Demo
 * 
 * Uncomment this file bellow to see demo
 * include QUICKFIELD_DIR . 'sample/sample.php';
 */
include QUICKFIELD_DIR . 'sample/sample.php';