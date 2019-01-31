<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Questions</title>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- Fonts -->  

        <!-- Styles -->
        <style>
     
        </style>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app" class="leading-normal">
            <div class="flex-center position-ref full-height">
                <div class="content">   
                    @yield('content')    
                </div>
            </div>
        </div>
    </body>
</html>
