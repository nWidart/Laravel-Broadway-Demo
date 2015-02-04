<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Parts Event Sourcing</title>
        <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.2/flatly/bootstrap.min.css" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="{{ Module::asset('parts:css/main.css') }}" rel="stylesheet">
    </head>
    <body>
        @include('parts::partials.navigation')
        <div class="container-fluid">
            <div class="row">
                @include('parts::partials.sidebar')
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    @yield('content')
                </div>
            </div>
        </div>

        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    </body>
</html>
