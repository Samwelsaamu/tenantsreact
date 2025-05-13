<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Server Error</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/icon.png') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
         <nav class="navbar navbar-expand-md bg-primary fixed-top shadow-sm">
            <div class="container text-center">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('assets/img/wagitonga.png') }}" alt="{{ App\Models\Agency::getAgencyName() }}" class="brand-image img-circle elevation-1" style="opacity: 1;border-radius: 15px 15px 1px 1px;margin-right: 2%;"> 
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav" >
                        <li class="nav-item">
                            <a class="nav-link" href="/homepage">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/allproperties">Properties</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/aboutus">About Us</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/contactus">Contact Us</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/gallery">Gallery</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="/home">Dashboard({{ Auth::user()->Fullname }})</a>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>


        <main class="" style="padding-top: 80px;min-height: calc(100vh - 3rem);">
            <div class="text-center">
              <h2 class="text-danger text-center">Some Technical Error has Occurred.</h2>
              <li class="nav-item">
                  <a class="nav-link" href="/home"><button class="btn btn-flat btn-primary"> <i class="fa fa-arrow-left"></i> {{ __('Go Back to  Dashboard') }}</button></a>
              </li>
          </div>
        </main>

         <footer class="main-footer bg-info ">
            <div class="float-right d-sm-block">
              <b>All rights reserved.</b>
            </div>
            <strong>Copyright &copy;  {{ App\Models\Agency::getAgencyName()}}.</strong> 
          </footer>
    </div>
</body>
</html>
