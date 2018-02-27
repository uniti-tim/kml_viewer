<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{config('app.name')}}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <script src="{{asset('js/app.js')}}"></script>


    </head>
    <body>

      <nav id="menu">
        <header>
          <h2>Menu</h2>
        </header>
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
