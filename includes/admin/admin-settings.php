<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ('is_admin' ) {


  //adds content and settings fields for settings page
  function eazy_photography_settings_page() { ?>
        <div class="wrap">
           <h1>Eazy Photography Portfolio</h1>
    
           <form method="post" action="options.php">
              <?php
                 settings_fields("settings");
                 do_settings_sections("eazy-photography-settings-");
                 submit_button(); 
              ?>
           </form>
        </div>
     <?php
  }

  //adds and registers the main plugin settings fields & section
  add_action("admin_init", "eazy_photography_settings");
  function eazy_photography_settings() {
      add_settings_section("settings", "Settings", null, "eazy-photography-settings-");
      
      add_settings_field("eazy-photo-settings-camera", "Exposure Settings: ", "eazy_photo_camera_settings_callback", "eazy-photography-settings-", "settings");  
      add_settings_field("eazy-photo-settings-maps", "Photo Location Maps: ", "eazy_photo_google_maps_callback", "eazy-photography-settings-", "settings");  
      add_settings_field("eazy-photo-settings-maps-api-key", "Google Maps API Key: ", "eazy_photo_google_maps_api_callback", "eazy-photography-settings-", "settings");  
      add_settings_field("eazy-photo-settings-comments", "Allow Comments: ", "eazy_photo_comments_settings_callback", "eazy-photography-settings-", "settings");  
      add_settings_field("eazy-photo-settings-author", "Show Author: ", "eazy_photo_author_settings_callback", "eazy-photography-settings-", "settings");      

      register_setting("settings", "eazy-photo-settings-camera");
      register_setting("settings", "eazy-photo-settings-maps");
      register_setting("settings", "eazy-photo-settings-maps-api-key");
      register_setting("settings", "eazy-photo-settings-comments");
      register_setting("settings", "eazy-photo-settings-author");
  }

  //adds html callback for google maps to the settings metabox
  function eazy_photo_google_maps_callback() { ?>
          <input type="checkbox" id="eazy-photo-settings-maps" name="eazy-photo-settings-maps" value="on" <?php checked('on', get_option('eazy-photo-settings-maps'), true); ?>>
          <label for="eazy-photo-settings-maps" ><?php __('on', 'eazy-photography'); ?>on</label>
    <?php
  }  
  //add html callback for google maps api key to the settings metabox
  function eazy_photo_google_maps_api_callback() { ?>
          <input type="text" id="eazy-photo-settings-maps-api-key" name="eazy-photo-settings-maps-api-key" value="<?php echo get_option('eazy-photo-settings-maps-api-key'); ?>">
    <?php
  }   
  //adds html callback for camera exposure settings to the settings metabox
  function eazy_photo_camera_settings_callback() { ?>
          <input type="checkbox" id="eazy-photo-settings-camera" name="eazy-photo-settings-camera" value="on" <?php checked('on', get_option('eazy-photo-settings-camera'), true); ?>>
          <label for="eazy-photo-settings-camera"><?php __('on', 'eazy-photography'); ?>on</label>
     <?php
  }  
  //adds html callback for comments on photos to the settings metabox
  function eazy_photo_comments_settings_callback() { ?>
          <input type="checkbox" id="eazy-photo-settings-comments" name="eazy-photo-settings-comments" value="on" <?php checked('on', get_option('eazy-photo-settings-comments'), true); ?>>
          <label for="eazy-photo-settings-comments"><?php __('on', 'eazy-photography'); ?>on</label>
     <?php
  }
  //adds html callback for showing photo author to the settings metabox
  function eazy_photo_author_settings_callback() { ?>
          <input type="checkbox" id="eazy-photo-settings-author" name="eazy-photo-settings-author" value="on" <?php checked('on', get_option('eazy-photo-settings-author'), true); ?>>
          <label for="eazy-photo-settings-author"><?php __('on', 'eazy-photography'); ?>on</label>
     <?php
  }    


}