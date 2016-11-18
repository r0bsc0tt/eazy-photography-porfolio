var map = new google.maps.Map(document.getElementById("map"), {
  center: {
    lat: parseFloat(EazyPhotoMap.latitude),
    lng: parseFloat(EazyPhotoMap.longitude),
  },
  zoom: 15
});

var marker = new google.maps.Marker({
  position: {lat: parseFloat(EazyPhotoMap.latitude), lng: parseFloat(EazyPhotoMap.longitude)},
  map: map
});

var infowindow = new google.maps.InfoWindow();