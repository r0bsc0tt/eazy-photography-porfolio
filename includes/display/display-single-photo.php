<?php 
if ( !defined( 'WPINC' ) ) { die; }

function check_photo_for_gallery($post) {
	if (strpos($post->post_content,'_masonry_layout="true"') === false){
?>
<script type="text/javascript">console.log("broken")</script>
<?php
	}else{
?>
<script type="text/javascript">console.log("working")</script>

<?php    
	//Add photo grid styles & scripts
	eazy_single_photo_styles_and_scripts();
	}
}


function fire_isotope_gallery() { ?>
  <script type="text/javascript">

  	// run isotope on gallery & gallery-items
    jQuery(document).imagesLoaded(function($) {
      var iso = new Isotope( '.gallery', {
        itemSelector: '.gallery-item',
      });
    });



  </script>
<?php }


function eazy_single_photo_styles_and_scripts() {
  global $wp_styles;  
  
  if (!is_admin()) {
    
    // isotope
    wp_register_script( 'eazy-photo-isotope', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.1/isotope.pkgd.min.js', array('jquery'), '3.0.1', true );
    wp_enqueue_script( 'eazy-photo-isotope' );
    
    // imagesloaded
    wp_register_script( 'eazy-photo-imagesloaded', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.1/imagesloaded.pkgd.min.js', array('jquery'), '3.0.1', true );
    wp_enqueue_script( 'eazy-photo-imagesloaded' );


    //add action to fire isotope
    add_action( 'wp_footer', 'fire_isotope_gallery', 50 );
  } 
}


/*************
ADMIN SETTINGS 
*************/
if ( is_admin() ) {
  add_action('print_media_templates', 'add_masonry_settings' );
}


function add_masonry_settings(){
  $current_screen = get_current_screen();
  if( $current_screen ->post_type === "eazy-photo" ) {?>

  <script type="text/html" id="tmpl-masonry-layout">
    <label class="setting">
      <span><?php _e('Masonry Layout'); ?></span>
      <input data-setting="_masonry_layout" type="checkbox">
    </label>
  </script>

  <script>

    jQuery(document).ready(function(){

      // add your shortcode attribute and its default value to the
      // gallery settings list; $.extend should work as well...
      _.extend(wp.media.gallery.defaults, {
        my_custom_attr: 'default_val'
      });

      // merge default gallery settings template with yours
      wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
        template: function(view){
          return wp.media.template('gallery-settings')(view)
               + wp.media.template('masonry-layout')(view);
        }
      });

    });

  </script>
  <?php
  }
  
}
