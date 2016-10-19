<?php
/**
 * Link
 * 
 * @package   Quick_Field/Corefields
 * @category  Functions
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Field Link
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_link( $settings, $value ) {
	ob_start();

	$id = isset( $settings['id'] ) ? $settings['id'] : $settings['name'];

	$link = quickfield_build_link( $value );

	$json_value = htmlentities( json_encode( $link ), ENT_QUOTES, 'utf-8' );

	$input_value = htmlentities( $value, ENT_QUOTES, 'utf-8' );
	?>
	<div class="quickfield-link" id="quickfield-link-<?php echo esc_attr( $id ) ?>">
		<?php printf( '<input type="hidden" name="%1$s" id="%5$s" class="%5$s %2$s" value="%3$s" data-json="%4$s"/>', $settings['name'], $settings['type'], $input_value, $json_value, $id ); ?>
		<a href="#" class="button link_button"><?php echo esc_attr__( 'Select URL', 'quickfield' ) ?></a> 
		<span class="link_label_title link_label"><?php echo esc_attr__( 'Title:', 'quickfield' ) ?></span> 
		<span class="title-label"><?php echo isset( $link['title'] ) ? esc_attr( $link['title'] ) : ''; ?></span> 
		<span class="link_label"><?php echo esc_attr__( 'URL:', 'quickfield' ) ?></span> 
		<span class="url-label">
			<?php
			echo isset( $link['url'] ) ? esc_url( $link['url'] ) : '';
			echo isset( $link['target'] ) ? ' ' . esc_attr( $link['target'] ) : '';
			?> 
		</span>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Build Link from string
 * 
 * @param string $value
 *
 * @since 1.0.0
 * @return array
 */
function quickfield_build_link( $value ) {
	return quickfield_parse_multi_attribute( $value, array( 'url' => '', 'title' => '', 'target' => '', 'rel' => '' ) );
}

/**
 * Print link editor template
 * Link field need function to work
 * 
 * @since 1.0.0
 * @return void
 */
function quickfield_link_editor_hidden() {
	echo '<textarea id="content" class="hide hidden"></textarea>';
	require_once ABSPATH . "wp-includes/class-wp-editor.php";
	_WP_Editors::wp_link_dialog();
}
