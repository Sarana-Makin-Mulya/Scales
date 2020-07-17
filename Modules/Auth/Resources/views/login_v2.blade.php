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
    /* Made with love by Mutiullah Samim*/

        html,body{
            background-image: url('http://getwallpapers.com/wallpaper/full/a/5/d/544750.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            height: 100%;
            font-family: 'Numans', sans-serif;
            }

        .container{
            height: 100%;
            align-content: center;
        }

        .card{
            height: 250px;
            margin-top: auto;
            margin-bottom: auto;
            width: 400px;
            background-color: rgba(255,255,255,0.7) !important;
        }

        .card-header h4{
            color: white;
        }


        .input-group-append span{
            width: 50px;
            background-color: #FFC312;
            color: black;
            border:0 !important;
        }

        input:focus{
            outline: 0 0 0 0  !important;
            box-shadow: 0 0 0 0 !important;
        }

        .remember{
            color: white;
        }

        .remember input
        {
            width: 20px;
            height: 20px;
            margin-left: 15px;
            margin-right: 5px;
        }

        .login_btn{
            color: black;
            background-color: #FFC312;
            width: 100px;
        }

        .login_btn:hover{
            color: black;
            background-color: white;
        }

        .links{
            color: white;
        }

        .links a{
            margin-left: 4px;
        }
    </style>
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header text-center mt-3">
				<h4>PT Sarana Makin Mulia</h4>
            </div>

            {{-- Error box --}}
            <div class="error-box text-center @error('username') mb-4 @enderror text-danger" role="alert">
                @error('username')
                    <span class="d-block">{{ $message }}</span>
                @enderror

                @error('password')
                    <span class="d-block">{{ $message }}</span>
                @enderror
            </div>

			<div class="card-body">
                <form action="{{ route('login') }}" method="POST">

                    @csrf

                    {{-- Username --}}
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Nama Pengguna">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <span class="fas fa-user"></span>
                            </span>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="input-group form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Remember Me button --}}
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>

                        {{-- Sign in button --}}
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                    </div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
