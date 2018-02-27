@extends('layouts.main')

@section('content')
<div id='map' style="width: 100%;height: 100vh;"></div>
<script type="text/javascript">
  Window.count = 0;
  function initMap(loc) {
        var options = {
          zoom: 10,
          center: (loc != undefined)? {lat: loc.lat, lng: loc.lng} :{lat: 30.735845, lng: -88.000542},
          disableDefaultUI: true
        }
        map = new google.maps.Map( document.getElementById('map'), options);

        var drawingManager = new google.maps.drawing.DrawingManager({
          drawingMode: google.maps.drawing.OverlayType.MARKER,
          drawingControl: true,
          drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: ['circle', 'polygon', 'rectangle']
          },
          circleOptions: {
            fillColor: '#ffff00',
            fillOpacity: .25,
            strokeWeight: 1.5,
            clickable: false,
            zIndex: 1
          },
          polygonOptions:{
            fillColor: '#ffff00',
            fillOpacity: .25,
            strokeWeight: 1.5,
            clickable: false,
            zIndex: 1
          },
          rectangleOptions:{
            fillColor: '#ffff00',
            fillOpacity: .25,
            strokeWeight: 1.5,
            clickable: false,
            zIndex: 1
          }
        });

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

        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            console.log('Drawn');
            //Find polygons in this field
        });

  }

  function makeInfoWindows(placemark,doc){
    var polygon = geoXML3.instances[geoXML3.instances.length-1].createPolygon(placemark, doc);
        if(polygon.infoWindow) {
          data = (placemark.vars.val.address.length > 0)? placemark.vars.val.address:placemark.name
            polygon.infoWindowOptions.content =
            '<div class="geoxml3_infowindow">\
                <h3>' + placemark.name +'</h3>\
                <!--<div data-poly="'+placemark.name+'" class="btn btn-primary"> Add to Selection</div>-->\
              </div>';
              polygon.id=Window.count;

              google.maps.event.addListener(polygon,'click',function() {
                if(polygon.fillColor != "#00FF00"){
                  polygon.setOptions({fillColor:"#00FF00",fillOpacity:0.7});
                }else{
                  polygon.setOptions({fillColor:"#0000ff",fillOpacity:0.48});
                  polygon.infoWindow.close()
                }

              })
    }
    Window.count++;
    return polygon;
  }

  function addToSelction(polygon){
    debugger;
  }
</script>
<script src="js/geoxml3.js"></script>
<script src="js/geoxml3_gxParse_kmz.js"></script>
<script src="js/ZipFile.complete.js"></script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCptxZlP6YYAIpqCTGvr6HjxD7UekNosk8&callback=initMap&libraries=drawing">
</script>
<script async defer src="js/ProjectedOverlay.js"></script>
@endsection
