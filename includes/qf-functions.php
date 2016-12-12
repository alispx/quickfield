<?php

/**
 * Helper functions
 *
 * @package   Quick_Field/Functions
 * @category  Functions
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */

/**
 * Parse string like "title:Quickfield is useful|author:vutuansw" to array('title' => 'Quickfield is useful', 'author' => 'vutuansw')
 *
 * @param $value
 * @param array $default
 *
 * @since 1.0.0
 * @return array
 */
function quickfield_parse_multi_attribute( $value, $default = array() ) {
	$result = $default;
	$params_pairs = explode( '|', $value );
	if ( !empty( $params_pairs ) ) {
		foreach ( $params_pairs as $pair ) {
			$param = preg_split( '/\:/', $pair );
			if ( !empty( $param[0] ) && isset( $param[1] ) ) {
				$result[$param[0]] = rawurldecode( $param[1] );
			}
		}
	}

	return $result;
}

function quickfield_init( $func ) {
	/**
	 * Hook to post screen
	 */
	if ( is_admin() ) {

		add_action( 'load-post.php', $func );
		add_action( 'load-post-new.php', $func );
	}
}
