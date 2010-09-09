<?php
/*
Plugin Name: Link Image Gallery
Plugin URI: http://www.joelpittet.com
Description: Add an image gallery link to the Advanced Link Box
Version: 1.0
Author: Joel Pittet
Author URI: http://www.joelpittet.com
*/


/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'lig_add_custom_box');


/* Adds a custom section to the "advanced" Post and Page edit screens */
function lig_add_custom_box() {

	if( function_exists( 'add_meta_box' )) {
		add_meta_box('linkadvanceddiv', __('Advanced'), 'custom_link_advanced_meta_box', 'link', 'normal', 'core');
		wp_enqueue_script('quicktags');
		add_filter( 'media_send_to_editor',  'media_send_to_custom_field', 15 );
		add_thickbox();
	}
}


function media_send_to_custom_field($html) {

	preg_match('/src="([^"]+)"/', $html, $matches);
	$html = $matches[1];
	$html = str_replace(site_url(), '', $html);
	?>
		<script type="text/javascript">
			var win = window.dialogArguments || opener || parent || top;
			win.send_to_custom_field("<?php echo trim(addslashes($html)) ?>");
			win.tb_remove();
		</script>
	<?php
	exit();

}


function custom_link_advanced_meta_box($link) {
?>

<?php if ( current_user_can( 'upload_files' ) ) : ?>
<script type="text/javascript">
	function send_to_custom_field(h) {
		jQuery('#link_image').val(h);
	}
</script>
<?php endif; ?>
	
<table class="form-table" style="width: 100%;" cellspacing="2" cellpadding="5">
	<tr class="form-field">
		<th valign="top"  scope="row"><label for="link_image"><?php _e('Image Address') ?></label></th>
		<td><input type="text" name="link_image" class="code" id="link_image" size="50" value="<?php echo ( isset( $link->link_image ) ? esc_attr($link->link_image) : ''); ?>" style="width: 95%" /> 
		
		<?php if ( current_user_can( 'upload_files' ) ) echo  _media_button(__('Add an Image'), 'images/media-button-image.gif?ver=20100531', 'image'); ?>
		</td>
	</tr>
	<tr class="form-field">
		<th valign="top"  scope="row"><label for="rss_uri"><?php _e('RSS Address') ?></label></th>
		<td><input name="link_rss" class="code" type="text" id="rss_uri" value="<?php echo  ( isset( $link->link_rss ) ? esc_attr($link->link_rss) : ''); ?>" size="50" style="width: 95%" /></td>
	</tr>
	<tr class="form-field">
		<th valign="top"  scope="row"><label for="link_notes"><?php _e('Notes') ?></label></th>
		<td><textarea name="link_notes" id="link_notes" cols="50" rows="10" style="width: 95%"><?php echo  ( isset( $link->link_notes ) ? $link->link_notes : ''); ?></textarea></td>
	</tr>
	<tr class="form-field">
		<th valign="top"  scope="row"><label for="link_rating"><?php _e('Rating') ?></label></th>
		<td><select name="link_rating" id="link_rating" size="1">
		<?php
			for ($r = 0; $r <= 10; $r++) {
				echo('            <option value="'. esc_attr($r) .'" ');
				if ( isset($link->link_rating) && $link->link_rating == $r)
					echo 'selected="selected"';
				echo('>'.$r.'</option>');
			}
		?></select>&nbsp;<?php _e('(Leave at 0 for no rating.)') ?>
		</td>
	</tr>
</table>
<?php
}



