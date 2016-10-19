<?php
/**
 * Image Picker
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
 * Field Image Picker.
 *
 * @param $settings
 * @param string $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function quickfield_form_image_picker( $settings, $value ) {
	ob_start();

	$id = isset( $settings['id'] ) ? $settings['id'] : $settings['name'];

	$multiple = isset( $settings['multiple'] ) ? absint( $settings['multiple'] ) : 1;
	?>
	<div class="quickfield-image_picker" data-multiple="<?php echo esc_attr( $multiple ) ?>" id="quickfield-image-<?php echo esc_attr( $id ) ?>">
		<?php
		printf( '<input type="hidden" name="%1$s" id="%2$s" class="attach_images %2$s %3$s" value="%4$s"/>', $settings['name'], $id, $settings['type'], $value );

		$value = explode( ',', trim( $value ) );
		?>
		<div class="attached_images">
			<ul class="image_list">
				<?php
				if ( !empty( $value[0] ) && sizeof( $value ) > 0 ) {
					foreach ( $value as $id ) {
						?>
						<li class="added" data-id="<?php echo esc_attr( $id ) ?>">
							<div class="inner">
								<?php echo wp_get_attachment_image( $id, 'thumbnail' ) ?>
							</div>
							<a href="#" class="remove" title="<?php echo esc_attr__( 'Remove', 'quickfield' ) ?>"></a>
						</li>
						<?php
					}
				}
				?>

			</ul>

			<a class="add_images" href="#" title="<?php echo esc_attr__( 'Add images', 'quickfield' ) ?>"><?php echo esc_attr__( 'Add images', 'quickfield' ) ?></a>

		</div>
	</div>
	<?php
	return ob_get_clean();
}
