
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'SMM-APP') }}</title>

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    {{-- Styles --}}
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    {{-- Internal Style --}}
    @yield('css')

    <style>
    .login-logo {
        font-size: 35px;
        font-weight: 300;
        margin-bottom: 25px;
        text-align: center;
    }
    </style>
</head>
<body class="hold-transition login-page bg-navy">
    <div class="login-box" id="app">
        {{-- Company Name OR logo --}}
        <div class="login-logo">
            <a href="{{ route('login') }}">
                <img src="{{ asset('images/logo-smm-no-text.png') }}" alt="PT Sarana Makin Mulya Logo" class="img-responsive">

            </a>
        </div>

        <div class="card">
            <div class="card-body login-card-body rounded pl-3 pr-3">
                <span class="text-center m-1 d-none"><h5 class="brand-sub-title">Selamat datang</h5></span>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Username --}}
                    <div class="input-group mb-3 mt-3">
                        <input type="text" name="username" class="form-control" placeholder="Nama Pengguna">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Error box --}}
                    <div class="error-box @error('username') mb-2 @enderror text-danger" role="alert" style="margin-top:-10px;">
                        @error('username')
                            <span class="d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Error box --}}
                    <div class="error-box @error('username') mb-2 @enderror text-danger" role="alert" style="margin-top:-10px;">
                        @error('password')
                            <span class="d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row mt-4">
                        {{-- Remember Me button --}}
                        <div class="col-8 mt-4">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>

                        {{-- Sign in button --}}
                        <div class="col-4 mt-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                    </div>
                </form>

                {{-- <p class="mb-1">
                    <a href="#">{{ __('I forgot my password') }}</a>
                </p> --}}
            </div>
        </div>
    </div>

    @include('partials._app-js')
    @yield('script')
    <script>
        localStorage.setItem("filter_level", "");
    </script>
</body>
</html>
