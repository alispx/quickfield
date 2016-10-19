<?php
/**
 * Map
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
 * Field map
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_map( $settings, $value ) {
	ob_start();
	$id = isset( $settings['id'] ) ? $settings['id'] : $settings['name'];
	?>
	<div class="quickfield-map" id="quickfield-map-<?php echo esc_attr( $id ) ?>">
	<?php printf( '<input type="hidden" name="%1$s" id="%2$s" class="%2$s %3$s" value="%4$s"/>', $settings['name'], $id, $settings['type'], $value ); ?>
		<div class="map_search">
			<input type="text" class="js-map_search"/>
			<i class="fa fa-search"></i>
		</div>
		<div class="map_canvas js-map_canvas"></div>
	</div>
	<?php
	return ob_get_clean();
}
