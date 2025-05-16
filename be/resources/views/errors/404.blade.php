<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>File or Resource not found</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

</head>
<body>
    <div id="app">

        <main class="" style="padding-top: 80px;min-height: calc(100vh - 3rem);">
            <div class="text-center">
              <h2 class="text-danger text-center" style="text-align:center;padding:10px;"> {{ config('app.name') }}</h2>
              <h4 class="text-danger text-center" style="text-align:center;padding:10px;">No Resource or file found</h4>
             
              <h4 class="text-danger text-center" style="text-align:center;padding:10px;">
                    <a style="text-align:center;padding:10px;" href={{config('app.url')}} >Back to HOME</a>
             </h4>
          </div>
        </main>

         
    </div>
</body>
</html>
