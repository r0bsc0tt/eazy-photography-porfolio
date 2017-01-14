function toggle_visibility(id) {
	var e = document.getElementById(id);
	if( e.style.display == 'block' ){
		e.style.display = 'none';
		e.className = 'eazy-photo-callback-off';
	}else{
		e.style.display = 'block';
		e.className = 'eazy-photo-callback-on';
	}
}

jQuery(".gallery-icon a").attr("href", "#");

function eazyPhotoSwitch() {
    var currImgObj = jQuery(".eazy-photo-single-img img")[0];
    var newImgObj = jQuery(this).find("img")[0];
    var currImgSizes = jQuery(".eazy-photo-single-img img").attr("sizes");
    var currImgSrc = currImgObj.currentSrc;
    var newImgSrc = newImgObj.currentSrc;

    if (currImgSrc !== newImgSrc) {
        var clone = $(newImgObj).clone();
        jQuery(".eazy-photo-single-img").html(clone);
        jQuery(".eazy-photo-single-img img").attr("sizes", currImgSizes);
    }

}

// switch images on mouseover
jQuery(".gallery-icon a").mouseover(eazyPhotoSwitch);
jQuery(".gallery-icon a").click(eazyPhotoSwitch);