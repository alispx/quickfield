<?php
/**
 * Sample Widget
 *
 * @package     Quickfield
 * @category    Sample
 * @author      vutuan.sw
 * @license     GPLv2 or later
 */

/**
 * Widget
 * Display in wp-admin/widgets.php
 */
class Qf_Example_Widget extends Qf_Widget {

	public function __construct() {

		$this->widget_cssclass = 'quickfield_example_widget';
		$this->widget_description = __( "Display the sample fields in the sidebar.", 'keplm' );
		$this->widget_id = 'quickfield_example_widget';
		$this->widget_name = __( 'QF Example Fields', 'keplm' );

		parent::__construct();
	}

	/**
	 * You should register fields in form function
	 */
	public function form( $instance ) {

		$this->fields = quickfield_example_fields();

		parent::form( $instance );
	}

	/**
	 * Widget output
	 */
	public function widget( $args, $instance ) {
		$this->widget_start( $args, $instance );
		//Your Content widget
		$this->widget_end( $args );
	}

}

/**
 * Init widget
 */
function quickfield_example_widget_init() {
	register_widget( 'Qf_Example_Widget' );
}

/**
 * Hook to widgets_init
 */
add_action( 'widgets_init', 'quickfield_example_widget_init' );
