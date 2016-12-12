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
	/**
	 * Css Class
	 */
	$css_class = 'quickfield-field quickfield-map';
	if ( !empty( $settings['el_class'] ) ) {
		$css_class.=' ' . $settings['el_class'];
	}
	
	/**
	 * Attributes
	 */
	$attrs = array();

	if ( !empty( $settings['name'] ) ) {
		$attrs[] = 'name="' . $settings['name'] . '"';
	}

	if ( !empty( $settings['id'] ) ) {
		$attrs[] = 'id="' . $settings['id'] . '"';
	}

	$attrs[] = 'data-type="' . $settings['type'] . '"';
	
	/**
	 * Support Customizer
	 */
	if ( !empty( $settings['customize_link'] ) ) {
		$attrs[] = $settings['customize_link'];
	}
	
	?>
	<div class=" <?php echo esc_attr( $css_class ) ?>" id="<?php echo esc_attr( uniqid('quickfield-map-') ) ?>">
		
		<?php printf( '<input type="hidden" class="qf_value" value="%1$s" %2$s/>', $value, implode( ' ', $attrs ) ); ?>
		
		<div class="map_search">
			<input type="text" class="js-map_search"/>
			<i class="fa fa-search"></i>
		</div>
		<div class="map_canvas js-map_canvas"></div>
		
	</div>
	<?php
	return ob_get_clean();
}
