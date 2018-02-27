@extends('layouts.main')

@section('content')
<div id='map' style="width: 100%;height: 100vh;"></div>
<script type="text/javascript">
  function initMap(loc) {
        var options = {
          zoom: 10,
          center: (loc != undefined)? {lat: loc.lat, lng: loc.lng} :{lat: 30.735845, lng: -88.000542},
          disableDefaultUI: true
        }
        map = new google.maps.Map( document.getElementById('map'), options);
        myParser = new geoXML3.parser({
          map: map,
          zoom: false,
          singleInfoWindow:true,
          markerOptions:{
            visible:false
          },
          createPolygon: makeInfoWindows
        });
        myParser.parse("{{asset('storage/City_Boundaries_Sample_Area.kml')}}");
  }

  function makeInfoWindows(placemark,doc){
    var polygon = geoXML3.instances[geoXML3.instances.length-1].createPolygon(placemark, doc);
        if(polygon.infoWindow) {
          data = (placemark.vars.val.address.length > 0)? placemark.vars.val.address:placemark.name
            polygon.infoWindowOptions.content =
            '<div class="geoxml3_infowindow">\
                <h3>' + placemark.name +'</h3>\
                <div onclick="addToSelction('+data+')" class="btn btn-primary"> Add to Selection</div>\
              </div>';
    }
    return polygon;
  }

  function addToSelction(data){
    //Add to selection window
  }
</script>
<script src="js/geoxml3.js"></script>
<script src="js/geoxml3_gxParse_kmz.js"></script>
<script src="js/ZipFile.complete.js"></script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCptxZlP6YYAIpqCTGvr6HjxD7UekNosk8&callback=initMap">
</script>
<script async defer src="js/ProjectedOverlay.js"></script>
@endsection
