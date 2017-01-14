<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ('is_admin' ) {

add_action( 'init', 'eazy_photo_category_register_meta' );
function eazy_photo_category_register_meta() {
    register_meta( 'photo-category', 'image-preview', 'eazy_photo_category_image' );
}


function eazy_photo_category_image($term_id, $alt = "") {
$img = get_term_meta( $term_id, 'image-preview', true  );
echo wp_get_attachment_image( $img, 'full', '', array( "class" => "attachment-full size-full", "alt" => $alt ) ); 
}


function get_eazy_photo_category_image( $term_id ) {
  $img = get_term_meta( $term_id, 'image-preview', true  );
  return $img;
}


function image_upload_form_html() {  ?>
    <div class='image-preview-wrapper'>
      <img id='image-preview' src='' width='100' height='100' style='max-height: 100px; width: 100px;'>
    </div>
    <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
    <input type='hidden' name='image_attachment_id' id='image_attachment_id' value=''>
<?php }


//scripts for upload to work
add_action( 'admin_footer', 'eazy_photo_category_image_upload' );
function eazy_photo_category_image_upload() {

  $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );

  ?><script type='text/javascript'>
    jQuery( document ).ready( function( $ ) {
      // Uploading files
      var file_frame;
      var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
      var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
      jQuery('#upload_image_button').on('click', function( event ){
        event.preventDefault();
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
          // Set the post ID to what we want
          file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
          // Open frame
          file_frame.open();
          return;
        } else {
          // Set the wp.media post id so the uploader grabs the ID we want when initialised
          wp.media.model.settings.post.id = set_to_post_id;
        }
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
          title: 'Select a image to upload',
          button: {
            text: 'Use this image',
          },
          multiple: false // Set to true to allow multiple files to be selected
        });
        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
          // We set multiple to false so only get one image from the uploader
          attachment = file_frame.state().get('selection').first().toJSON();
          // Do something with attachment.id and/or attachment.url here
          $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
          $( '#image_attachment_id' ).val( attachment.id );
          // Restore the main post ID
          wp.media.model.settings.post.id = wp_media_post_id;
        });
          // Finally, open the modal
          file_frame.open();
      });
      // Restore the main ID when the add media button is pressed
      jQuery( 'a.add_media' ).on( 'click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
      });
    });
  </script><?php
}


// create image upload on add new term page
add_action( 'photo-category_add_form_fields', 'eazy_photo_new_term_color_field' );
function eazy_photo_new_term_color_field() {
  wp_enqueue_media(); ?>
  <div class="form-field form-required term-name-wrap">
  <label for="tag-name">Name</label>
  <?php image_upload_form_html(); ?>
  </div> <?php
}

// create image upload on edit term page
add_action( 'photo-category_edit_form_fields', 'eazy_photo_edit_term_color_field' );
function eazy_photo_edit_term_color_field() {
  wp_enqueue_media(); ?>
    <tr class="form-field term-img-wrap">
    <th scope="row"><label for="img">Category Image</label></th>
    <td>
  <?php image_upload_form_html(); ?>
    </td>
  </tr>
<?php }


// save the image
add_action( 'edit_photo-category',   'eazy_photo_save_term_color' );
add_action( 'create_photo-category', 'eazy_photo_save_term_color' );
function eazy_photo_save_term_color($term_id) {
  $img_id = $_POST['image_attachment_id'];
  $old_img = get_eazy_photo_category_image( $term_id );
  $new_img = isset( $_POST['image_attachment_id'] ); 

  if ( $old_img && '' === $new_img ) {
    delete_term_meta( $term_id, 'image-preview' );
  }
  elseif ( $old_img !== $new_img ) {
    update_term_meta( $term_id, 'image-preview',   $img_id );
  }
}
}