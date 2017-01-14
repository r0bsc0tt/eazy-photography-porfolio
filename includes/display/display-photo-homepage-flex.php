<?php 
if ( !defined( 'WPINC' ) ) { die; }

function get_homepage_flexboxes() {
  $cats = get_terms('photo-category'); ?>
    <div class="homepage-photo-cat-holder"><?php
      foreach ($cats as $key ) {
          //print_r($key); ?>
        <div class="homepage-photo-cat" id="<?php echo $key->slug; ?>">
            <a href="<?php echo get_term_link( $key ); ?>" >
              <?php echo $key->name; ?>
              <?php eazy_photo_category_image($key->term_id, $key->name); ?>
            </a>
        </div><?php  
      }?>
    </div><?php
}