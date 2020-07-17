<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SMM-APP') }}</title>

    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700" rel="stylesheet"> --}}

    {{-- Internal Style --}}
    @yield('css')

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>
<body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
    {{-- Site Wrapper --}}
    <div class="wrapper" id="app">
        {{-- Top navbar --}}
        @include('partials._navbar')
        @include('partials._sidebar-left')

        {{-- Content --}}
        <div class="content-wrapper px-2 pb-4">
            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    @include('partials._footer')

    {{-- Js --}}
    @include('partials._app-js')
    @yield('main-script')

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(document).ready(function () {
            $filter_level = localStorage.getItem("filter_level");
            if($filter_level==""){
                localStorage.setItem("filter_level", 2);
            }
            $("#filter_level").val(localStorage.getItem("filter_level"));
            $('#filter_level').on('change', function () {
                var level = $(this).val(); // get selected value
                axios.get(window.location.origin + `/user/control-level?level=${level}`)
                .then(response => {
                    localStorage.setItem("filter_level", response.data);
                    window.location = window.location.origin + "/home";
                })
                .catch(error => console.log(error));
            });

            setTimeout(function() {
                $('body').addClass('sidebar-collapse');
            }, 120);
        })

    </script>

    @yield('script')
</body>
</html>
