<?php 
if ( !defined( 'WPINC' ) ) { die; }


function fire_isotope() { ?>
  <script type="text/javascript">
    //document.addEventListener("DOMContentLoaded", function(event) { 
    jQuery(document).imagesLoaded(function($) {

      // init Isotope
      var iso = new Isotope( '.grid', {
        itemSelector: '.grid-item',
        masonry: { columnWidth: '.grid-sizer' }
      });

      // bind filter button click
      var filtersElem = document.querySelector('.filters-button-group');
      filtersElem.addEventListener( 'click', function( event ) {
        // only work with buttons
        if ( !matchesSelector( event.target, 'button' ) ) {
          return;
        }
        var filterValue = event.target.getAttribute('data-filter');
        // use matching filter function
        //filterValue = filterValue;
        iso.arrange({ filter: filterValue });
        
      });

    });
  </script>
<?php }


//Add photo grid styles & scripts
add_action( 'wp_enqueue_scripts', 'eazy_photo_grid_styles_and_scripts', 999 ); 
function eazy_photo_grid_styles_and_scripts() {
  global $wp_styles;  
  if (!is_admin()) {
  
    if ( is_post_type_archive( 'eazy-photo' ) || is_tax( 'photo-collection' ) || is_tax( 'photo-category' )) {
      // isotope (filter and masonry layout for portfolio)
      wp_register_script( 'eazy-photo-isotope', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.1/isotope.pkgd.min.js', array('jquery'), '3.0.1', true );
      wp_enqueue_script( 'eazy-photo-isotope' );
      
      // isotope (filter and masonry layout for portfolio)
      wp_register_script( 'eazy-photo-imagesloaded', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.1/imagesloaded.pkgd.min.js', array('jquery'), '3.0.1', true );
      wp_enqueue_script( 'eazy-photo-imagesloaded' );

      //add action to fire isotope
      add_action( 'wp_footer', 'fire_isotope', 50 );


    }

  }
}



function eazy_photo_grid($post, $tax_type) {
  $tn_id = get_post_thumbnail_id( $post->ID );
  $img = wp_get_attachment_image_src( $tn_id, 'eazy-photo-thumb-m' );
  $width = $img[1];
  $height = $img[2]; ?>

  <?php if ($width > $height){ ?>
    <div id="photo-<?php the_ID(); ?>" class="eazy-photo-gallery-landscape grid-item <?php get_this_photos_terms_as_tags($tax_type); ?> cf"  data-category="<?php get_this_photos_terms_as_tags($tax_type); ?>" >
      <a href="<?php the_permalink(); ?>" title="<?php printf(__('View %s', 'galleria'), get_the_title()); ?>">
        <?php if ( has_post_thumbnail() ) : ?>
          <?php the_post_thumbnail( 'eazy-photo-thumb-m', array('class' => 'eazy-photo') ); ?>
        <?php endif; ?>
      </a>
      <div class="eazy-photo-gallery-item-title"><h3><?php the_title(); ?></h3></div>
    </div>
  <?php } else{ ?>
    <div id="photo-<?php the_ID(); ?>" class="eazy-photo-gallery-portrait grid-item <?php get_this_photos_terms_as_tags($tax_type); ?> cf" data-category="<?php get_this_photos_terms_as_tags($tax_type); ?>" >
      <a href="<?php the_permalink(); ?>" title="<?php printf(__('View %s', 'galleria'), get_the_title()); ?>">
        <?php if ( has_post_thumbnail() ) : ?>
          <?php the_post_thumbnail('eazy-photo-thumb-m', array('class' => 'eazy-photo')); ?>
        <?php endif; ?>
      </a>
      <div class="eazy-photo-gallery-item-title"><h3><?php the_title(); ?></h3></div>      
    </div>
    
  <?php } 
}
