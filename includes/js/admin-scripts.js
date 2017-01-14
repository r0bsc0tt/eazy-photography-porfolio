	var mapType = jQuery('#eazy_camera_map_type');
	var select = this.value;


	if (mapType.val() == 'iframe') {
	    jQuery('#iframe-settings').show();
	    jQuery('#javascript-settings').hide();
	}
	else if (mapType.val() == 'javascript') {
	    jQuery('#javascript-settings').show();
		jQuery('#iframe-settings').hide();
	}
	else {
		jQuery('#javascript-settings').hide();
		jQuery('#iframe-settings').hide();
	}




	mapType.change(function () {

		if (jQuery(this).val() == '') {
			jQuery('#javascript-settings').hide();
			jQuery('#iframe-settings').hide();
		}
		if (jQuery(this).val() == 'iframe') {
		    jQuery('#javascript-settings').hide();
		    jQuery('#iframe-settings').show();  
		}
		if (jQuery(this).val() == 'javascript') {
		    jQuery('#iframe-settings').hide();
		    jQuery('#javascript-settings').show();
		}

	});
