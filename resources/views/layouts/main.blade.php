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
        @yield('menu')
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

      <!-- Upload KML Modal -->
      <div class="modal fade" id="uploadKML" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center">Upload New KML</h4>
            </div>
            <div class="modal-body" style="height:150px">
              <p class="text-center">Click below to upload a new file. Files with the same name will be overwritten!</p>
              <div class="col-xs-12 text-center">
                {!!Form::open(['url'=>'upload/kml','enctype' => 'multipart/form-data'])!!}
                  <label class='btn btn-default' for="upload-kml">Browse for KML</label>
                  <input type="file" name="uploadKML" id="upload-kml"  onchange="javascript:showFilename()" />
                  <div id="fileList" style="list-style:none;"></div>
                  <br><br>

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              {{Form::submit('Upload',['class'=>'btn btn-success'])}}
            </div>
          {!!Form::close()!!}
          </div>
        </div>
      </div>

      <script type="text/javascript">
      function showFilename(){
        var input = document.getElementById('upload-kml');
        var output = document.getElementById('fileList');

        output.innerHTML = '<ul>';
        for (var i = 0; i < input.files.length; ++i) {
          output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
        }
        output.innerHTML += '</ul>';
      }
      </script>


    </body>
</html>
