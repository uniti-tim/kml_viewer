<?php
$storage = Storage::allFiles('public');
$files =[];
  foreach( $storage as $file){
    if( strpos( strtolower(basename($file)),'.kml') ){
      $files[] = basename($file);
    }
  }
 ?>

<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name='page' content="{{ $name }}">
        <meta name='kml' content="{{ $kml }}">

        <title>{{config('app.name')}} :: {{$title}}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <script src="{{asset('js/app.js')}}"></script>


    </head>
    <body>

      <nav id="menu">
        <div class="col-xs-12">
          <h2>KML Files</h2>
          <select class="kml-picker selectpicker" data-live-search="true" data-with='300px'>
            <option value="" selected>Select a KML File</option>
            @foreach($files as $file)
            <option value="{{$file}}" {{ (request()->kml === $file)? 'selected':null }}>{{$file}}</option>
            @endforeach
          </select>
        </div>

        <div class="col-xs-12 selection-container">
          <h2>Selection</h2>
          <table class='table'>
            <thead>
              <th>Name</th>
              <th>Gis ID</th>
              <th></th>
            </thead>
            <tbody data-selections>
            </tbody>
          </table>
          <div style='display:none' class="export-btn btn btn-default">Export</div>
        </div>

      </nav>

      <main id="panel">
        <header>
          <div id="menu-icon" class="slideout-menu-toggle">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </header>
              @yield('content')
      </main>


    </body>
</html>
