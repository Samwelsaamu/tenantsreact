<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

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
                    <img src="{{ asset('assets/img/wagitonga1.png') }}" alt="Wagitonga Logo" class="brand-image elevation-1 m-0 " style="opacity: 1;width:150px;border-radius: 15px 15px 1px 1px;margin-right: 2%;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mx-auto" >
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
            @yield('content')
        </main>

         <footer class="main-footer text-center bg-info text-white" style="bottom:1%;width:100%;">
            <strong> &copy;  {{ App\Models\Agency::getAgencyName()}}.</strong> 
          </footer>
    </div>
</body>
</html>
