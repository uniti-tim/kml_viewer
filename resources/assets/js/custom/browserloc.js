
$(function(){if(Window.Page === 'home'){
  if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(function(position) {
      let loc ={lat:position.coords.latitude,lng:position.coords.longitude}
      initMap(loc);
    });
  }
}})
