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
	$term_args = array(
		'id' => 'quickfield_example_category',
		'pages' => array( 'category' ), //Display in category screen
		'fields' => quickfield_example_fields()//Array fields
	);
	new Qf_Term_Meta( $term_args );
}

add_action( 'admin_init', 'quickfield_example_category' );

