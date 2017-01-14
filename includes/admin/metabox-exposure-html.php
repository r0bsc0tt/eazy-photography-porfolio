<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ('is_admin' ) {

  //returns multi dimensional array of shutter speeds
  function all_shutter_speed_vals() { 
    return array( 
      array("0.0001221", "1/8000", "full-stop"), array("0.0001538", "1/6400", "third-stop"), array("0.0001938", "1/5000", "third-stop"), 
      array("0.0002441", "1/4000", "full-stop"), array("0.0003076", "1/3200", "third-stop"), array("0.0003875", "1/2500", "third-stop"), array("0.00048828", "1/2000", "full-stop"), array("0.0006152", "1/1600", "third-stop"), 
      array("0.0007751", "1/1250", "third-stop"), array("0.0009766", "1/1000", "full-stop"), array("0.0012304", "1/800", "third-stop"), array("0.0015502", "1/640", "third-stop"), array("0.0019531", "1/500", "full-stop"), 
      array("0.0024608", "1/400", "third-stop"), array("0.0031004", "1/320", "third-stop"), array("0.0039063", "1/250", "full-stop"), array("0.0049216", "1/200", "third-stop"), array("0.0062008", "1/160", "third-stop"), 
      array("0.0078125", "1/125", "full-stop"), array("0.0098431", "1/100", "third-stop"), array("0.0124016", "1/80", "third-stop"), array("0.015625", "1/60", "full-stop"), array("0.0196863", "1/50", "third-stop"), 
      array("0.0248031", "1/40", "third-stop"), array("0.03125", "1/30", "full-stop"), array("0.0393725", "1/25", "third-stop"), array("0.0496063", "1/20", "third-stop"), array("0.0625", "1/15", "full-stop"), 
      array("0.0787451", "1/13", "third-stop"), array("0.0992126", "1/10", "third-stop"), array("0.125", "1/8", "full-stop"), array("0.1574901", "1/6", "third-stop"), array("0.1984251", "1/5", "third-stop"), 
      array("0.25", "1/4", "full-stop"), array("0.3149803", "1/3", "third-stop"), array("0.3968503", "1/2.5", "third-stop"), array("0.5", "1/2", "full-stop"), array("0.6299605", "1/1.6", "third-stop"), 
      array("0.7937005", "1/1.3", "third-stop"), array("1.0", "1 second", "full-stop"), array("1.2599", "1.3\"", "third-stop"), array("1.5874", "1.6\"", "third-stop"), array("2", "2\"", "full-stop"),
      array("2.5198", "2.5\"", "third-stop"), array("3.1748", "3\"", "third-stop"), array("4", "4\"", "full-stop"), array("5.0397", "5\"", "third-stop"), array("6.3496", "6\"", "third-stop"), 
      array("8", "8\"", "full-stop"), array("10.0794", "10\"", "third-stop"), array("12.6992", "13\"", "third-stop"), array("16", "16\"", "full-stop"), array("20.1587", "20\"", "third-stop"), 
      array("25.3984", "25\"", "third-stop"), array("32", "30\"", "full-stop"), array("40.3174", "40\"", "third-stop"), array("50.7968", "50\"", "third-stop"), array("64", "60\"", "full-stop"),   );
  }

  // returns an array of aperture values
  function all_aperture_vals() {
    return  array("1", "1.1", "1.2", "1.4", "1.6", "1.8", "2", "2.2", "2.5", "2.8", "3.2", "3.5", "4", 
      "4.5", "5.0", "5.6", "6.3", "7.1", "8", "9", "10", "11", "13", "14", "16", "18", "20", "22", "25", "28", "32", 
      "36", "40", "45", "50", "57", "64", "72", "80", "90");
  }

  /** 
  * Used to get the closest shutter speed value to the exif data. 
  * Takes in one parameters for the current shutter speed in the exif data,
  * it then finds the closest shutter speed value to the exif data. 
  *  
  * @param string $exif_tv Exif_tv references the exif data's shutter speed.
  *
  * @return string Returns the closest shutterspeed value from the array of shutterspeed values.
  **/
  function get_closest_shutter_speed($exif_tv) {
    $all_shutter_speed_vals = all_shutter_speed_vals();
    $exif_tv = floatval($exif_tv);
    $newvals = array();
    foreach ($all_shutter_speed_vals as $tv_array => $value) {
      $floatval = floatval($value[0]);
      if ( $exif_tv >= $floatval ) {
        $newvals[] = $value;
      }
    }
    $new_tv = end($newvals);
    return $new_tv[0];
  }

  /** 
  * Used to mark an option selected in the admin dropdown for aperture value. 
  * Takes in two parameters, one for the current aperture in the database
  * the second value is the current aperture value in the loop, if they match selected is returned. 
  * @param string $current_av Used for the current aperture value.
  * @param string $val Used for current aperture value in a loop.
  *
  * @return string Returns selected if aperture value matches current value.
  **/
  function is_av_val_selected($current_av, $val) {
    $exif_av = eazy_photo_exif_info_get_value('aperture');
    if ($current_av == $val) {
      return "selected";
    }elseif ($exif_av == $val) {
      return "selected";
    }
  }


  /** 
  * Creates the select elements dropdown of aperture values options.  
  * @param string $current_av Used for the current aperture value.
  * @param string $this_name Used for pass in a ID and name property for the select element (optional).
  *
  * @return string Returns selected if aperture value matches current value.
  **/
  function get_aperture_values($current_av, $this_name="eazy_camera_settings_aperture") { 
    $all_aperture_vals = all_aperture_vals();
    ?>
    <select name="<?php echo $this_name; ?>" id="<?php echo $this_name; ?>">
        <option value=""></option>
      <?php $i = 3;
      foreach ($all_aperture_vals as $aperture_value) {
        if ($i % 3 == 0) { ?>
          <option value="<?php echo $aperture_value; ?>" class="full-stop" <?php echo is_av_val_selected($current_av, $aperture_value); ?> > <?php
        }else { ?>
          <option value="<?php echo $aperture_value; ?>" class="third-stop" <?php echo is_av_val_selected($current_av, $aperture_value); ?> > <?php
        }?>
            f/<?php echo $aperture_value; ?>
          </option><?php 

         $i++;
      }?>
    </select><?php
  }


  /** 
  * Used to mark an option selected in the admin dropdown for shutter speed value. 
  * Takes in two parameters, one for the current shutter speed in the database
  * the other value is the shuter speed value in the loop, if they match selected is returned. 
  * @param string $current_tv Used for the current shutter speed value.
  * @param string $val Used for current shutter speed value in a loop
  *
  * @return string Returns selected if shutter speed value matches current value.
  **/  
  function is_tv_val_selected($current_tv, $val) {
    $exif_tv = eazy_photo_exif_info_get_value('shutter_speed');
    
    if ($current_tv == $val) {
      echo "selected";
    } elseif ($exif_tv == $val) {
      echo "selected";
    } else {
      
    }

  }


  /** 
  * Creates the select dropdown of shutter speed values options.  
  * @param string $current_tv Used for the current shutter speed value.
  * @param string $this_name Used for pass in a ID and name property for the select element (optional).
  *
  * @return string Returns selected if aperture value matches current value.
  **/
  function get_shutter_speed_values($current_tv, $this_name="eazy_camera_settings_shutter_speed") { 
  $all_shutter_speed_vals = all_shutter_speed_vals(); ?>
    <select name="<?php echo $this_name; ?>" id="<?php echo $this_name; ?>">
        <option value=""></option>
      <?php 
      foreach ($all_shutter_speed_vals as $shutter_speed_val) { ?>
        <option value="<?php echo $shutter_speed_val[0]; ?>" class="<?php echo $shutter_speed_val[2]; ?>" <?php is_tv_val_selected($current_tv, $shutter_speed_val[0]); ?> >
          <?php echo $shutter_speed_val[1]; ?>
        </option><?php 
      } ?>
    </select><?php
  }


  /** 
  * Used inside a loop to get the thumnail ID, which gets the attachement metadata, and put into an array.
  *    
  * @return array Returns an array of meta info available to image.
  **/
  function eazy_photo_exif_info_array() {
    $thisthumbid = get_post_thumbnail_id( get_the_id() );
    $thismeta = wp_get_attachment_metadata( $thisthumbid);
    if ($thismeta  != '') {
      $eazy_photo_exif_info = array();
      foreach ($thismeta['image_meta'] as $key => $value) { 
        $eazy_photo_exif_info[$key] = $value;
      }
      return $eazy_photo_exif_info;
    }
  }


  /** 
  * Takes a keyname from the eazy_photo_exif_info_array array and returns the value. 
  * 
  * @param string $keyname Accepts a keyname from the array returned by eazy_photo_exif_info_array.
  *
  * @return string Returns the value for the keyname used as a parameter.
  **/
  function eazy_photo_exif_info_get_value($keyname) {
    $eazy_photo_meta = eazy_photo_exif_info_array();
    if (in_array($eazy_photo_meta[$keyname], array('0', '', NULL)) != TRUE ) {
      return $eazy_photo_meta[$keyname];
    }else {
      return NULL;
    }
  }


}