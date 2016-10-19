<?php
/**
 * Image Background
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
 * Field Image Background.
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_image_background( $settings, $value ) {
	ob_start();

	$id = isset( $settings['id'] ) ? $settings['id'] : $settings['name'];

	$multiple = isset( $settings['multiple'] ) ? absint( $settings['multiple'] ) : 1;
	?>
	<div class="quickfield-image_background" data-multiple="<?php echo esc_attr( $multiple ) ?>" id="quickfield-image_background-<?php echo esc_attr( $id ) ?>">
		<?php
		printf( '<input type="hidden" name="%1$s" id="%2$s" class="attach_images %2$s %3$s" value="%4$s"/>', $settings['name'], $id, $settings['type'], $value );

		$image = $added = '';
		if ( !empty( $value ) ) {
			$image = wp_get_attachment_image_url( $value, 'full' );
			if ( !empty( $image ) ) {
				$image = sprintf( 'style="background-image:url(%s)"', $image );
				$added = 'added';
			}
		}
		?>
		<div class="attachment-media-view <?php echo esc_attr( $added ) ?>" <?php print $image ?>>
			<div class="placeholder">
				<?php echo esc_html__( 'No image selected', 'quickfield' ) ?>, <a class="add_image" href="#"><?php echo esc_attr__( 'Set background image', 'quickfield' ) ?></a>				
			</div>
			<a href="#" class="remove" title="<?php echo esc_attr__( 'Remove', 'quickfield' ) ?>"></a>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
