@extends('layouts.main')

@section('content')
<input id="pac-input" class="controls" type="text" placeholder="Search City, Address, or Place">
<div id='map' style="width: 100%;height: 100vh;"></div>
<script type="text/javascript">
Window.loaded = 0;
  function initMap(loc) {
        var options = {
          zoom: 10,
          center: (loc != undefined)? {lat: loc.lat, lng: loc.lng} :{lat: 30.735845, lng: -88.000542},
          disableDefaultUI: true
        }
        map = new google.maps.Map( document.getElementById('map'), options);

        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

        var drawingManager = new google.maps.drawing.DrawingManager({
          drawingMode: google.maps.drawing.OverlayType.NONE,
          drawingControl: true,
          drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: ['polygon']
          },
          polygonOptions:{
            fillColor: '#ffff00',
            fillOpacity: .25,
            clickable: false,
            zIndex: 1
          },
        });

        myParser = new geoXML3.parser({
          map: map,
          zoom: false,
          singleInfoWindow:true,
          markerOptions:{
            visible:false
          },
          processStyles: false,
          createPolygon: makeInfoWindows
        });

        if( Window.loaded ){
          @if( request()->kml != null )
            @if(\App::environment('local'))
              myParser.parse("{{asset('storage/'.request()->kml)}}");
            @else
              myParser.parse("{{Storage::url('kmls/'.request()->kml)}}");
            @endif
          @endif
        }
        Window.loaded++
        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            selectPolygons(event.overlay);
            polygon_fadeout(event.overlay,2,function(){event.overlay.setMap(null)})
        });

        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });


        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }

            if (place.geometry.viewport) {
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });

  }
  Window.count = 0;
  function makeInfoWindows(placemark,doc){
    var polygon = geoXML3.instances[geoXML3.instances.length-1].createPolygon(placemark, doc);
        if(polygon.infoWindow) {

          uid = $( $.parseHTML(placemark.description)[5] ).children().find('td:contains("UID")').eq(1).next().text()
          data = (uid.length > 0)? uid:placemark.name

            polygon.infoWindowOptions.content =
            '<div class="geoxml3_infowindow">\
                <h3>' + placemark.name +'</h3>\
                <!--<div data-poly="'+placemark.name+'" class="btn btn-primary"> Add to Selection</div>-->\
              </div>';
            polygon.i = Window.count;
            polygon.id = uid;

              google.maps.event.addListener(polygon,'click',function() {
                if(polygon.fillColor != "#00FF00"){//if not selected
                  polygon.setOptions({fillColor:"#00FF00",fillOpacity:0.7});
                  addToTable(polygon);
                }else{
                  polygon.setOptions({fillColor:"#0000ff",fillOpacity:0.48});
                  polygon.infoWindow.close();
                  removeFromTable(polygon.i);
                }
                toggleExport();
              })
    }
    Window.count++;
    return polygon;
  }
  function selectPolygons(overlay){
    if(geoXML3.instances[0].docs[0] === undefined){return true}

    geoXML3.instances[0].docs[0].placemarks.forEach(function(el){
      let NE = el.polygon.bounds.getNorthEast();
      let SW = el.polygon.bounds.getSouthWest();
      if( google.maps.geometry.poly.containsLocation(NE,overlay) && google.maps.geometry.poly.containsLocation(SW,overlay) ){
        try{
          google.maps.event.trigger(el.polygon,'click');
        }catch(e){}
      }
    })
  }

  function polygon_fadeout(polygon, seconds, callback){
    var
    fill = (polygon.fillOpacity*50)/(seconds*999),
    stroke = (polygon.strokeOpacity*50)/(seconds*999),
    fadeout = setInterval(function(){
        if(polygon.fillOpacity <= 0.0){
            clearInterval(fadeout);
            polygon.setVisible(false);
            if(typeof(callback) == 'function'){
                callback()
            }
            return;
        }
        polygon.setOptions({
            'fillOpacity': Math.max(0, polygon.fillOpacity-fill),
            'strokeOpacity': Math.max(0, polygon.strokeOpacity-stroke)
        });
    }, 50);
  }

  function addToTable(polygon){
    let newRow ="<tr data-poly='"+polygon.i+"' data-id='"+polygon.id+"'>\
      <td>"+polygon.title.split('(')[0]+"</td>\
      <td>"+polygon.id+"</td>\
      <td>\
        <a onclick='removeFromSelection("+polygon.i+")'><i style='color:#FFF;cursor:pointer' class='fa fa-trash'></i></a>\
      </td>\
    </tr>\
    ";
    $('tbody[data-selections]').append(newRow);
  }

  function removeFromSelection(i){
    google.maps.event.trigger(geoXML3.instances[0].docs[0].placemarks[i].polygon,'click');
  }
  function removeFromTable(i){
    $('tr[data-poly="'+i+'"]').remove();
  }
  function toggleExport(){
    if( $('tbody[data-selections]').children().length > 0 ){
      $('.export-btn').show();
    }else{
      $('.export-btn').hide();
    }
  }

</script>
<script src="{{asset('js/geoxml3.js')}}"></script>
<script src="{{asset('js/geoxml3_gxParse_kmz.js')}}"></script>
<script src="{{asset('js/ZipFile.complete.js')}}"></script>
<script async
src="https://maps.googleapis.com/maps/api/js?key={{$_ENV['GMAPS_KEY']}}&callback=initMap&libraries=drawing,places">
</script>
<!-- <script async defer src="js/ProjectedOverlay.js"></script> -->
@endsection


@section('menu')
<?php
if(App::environment('local')){
  $storage = Storage::disk('local')->allFiles('public');
}else{
  $storage = Storage::disk('s3')->allFiles('kmls');
}
$files =[];
  foreach( $storage as $file){
    if( strpos( strtolower(basename($file)),'.kml') || strpos( strtolower(basename($file)),'.kmz') ){
      $files[] = basename($file);
    }
  }
 ?>

<div class="col-xs-12">
  <h2>KML Files <a data-toggle="modal" data-target="#uploadKML"><i style='color:#FFF' class='fa-fw fa fa-upload'></i></a>
  </h2>
  <select class="kml-picker selectpicker" data-live-search="true" data-with='300px'>
    <option value="" selected>Select a KML File</option>
    @foreach($files as $file)
    <option value="{{$file}}" {{ (request()->kml === $file)? 'selected':null }}>{{$file}}</option>
    @endforeach
  </select>

</div>

<div class="col-xs-12 selection-container">
  <h2>Selection</h2>
  <a>
    <div style='display:none;margin-top: 5px;' class="export-btn btn btn-default">Modify Selection Data</div>
  </a>

  <h6>or</h6>
  <h6> Export Selection </h6>
    <select class="selectpicker export-format">
      <option data-icon="fa fa-file-excel-o" value="table">Selection Table</option>
      <option data-icon="fa fa-file-excel-o" value="tabledata">Selection + Data</option>
    </select>
    <a onclick="exportData()">
      <div style='display:none;margin-top: 5px;' class="export-btn btn btn-default">Export</div>
    </a>
  <table class='table'>
    <thead>
      <th>Name</th>
      <th>Gis ID</th>
      <th></th>
    </thead>
    <tbody data-selections>
    </tbody>
  </table>
</div>

<script type="text/javascript">
  function exportData(){
    console.log('%c Exporting Selection Data...', 'background: #222; color: #bada55');
    let data = [];
    $('tbody[data-selections]').children().get().forEach(function(el){
      //pushes an array into an array containing the UID and HR Name
      data.push([$(el).data('id'),$(el).children().get(0).innerHTML]);
    })

    var download = window.open(
      "generator/excel?data="+JSON.stringify(data)+"&name="+$('.kml-picker').selectpicker('val')+"&format="+$('.export-format').selectpicker('val'),
      '_blank'
    );

  }
</script>
@endsection
