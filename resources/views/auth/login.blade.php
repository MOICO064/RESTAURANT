<!DOCTYPE html>
<html lang="en" style="height: auto;">

@php
    $background = asset(system_info('cover') ? 'uploads/' . system_info('cover') : 'dist/img/no-image-available.png');
@endphp

@include('layouts.header')

<body class="hold-transition login-page" onload="end_loader()">
    <style>
        body {
            background-image: url("{{ $background }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            backdrop-filter: contrast(1);
        }

        #page-title {
            text-shadow: 6px 4px 7px black;
            font-size: 3.5em;
            color: #fff4f4 !important;
            background: #8080801c;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            /* para Safari */
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        }

        .glass-input {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 8px;
            padding: 10px 15px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: border 0.3s, box-shadow 0.3s, background 0.3s;
        }

        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .glass-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
            color: #fff;
        }

        .glass-switch .form-check-input {
            width: 2.5em;
            height: 1.4em;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            transition: all 0.3s ease-in-out;
        }

        .glass-switch .form-check-input:checked {
            background-color: rgba(255, 255, 255, 0.6);
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.4);
        }

        .glass-switch .form-check-label {
            margin-left: 10px;
        }
    </style>


    <script>
        start_loader();
    </script>

    <h1 class="text-center text-white px-4 py-5" id="page-title"><b>{{ system_info('name') ?? config('app.name') }}</b>
    </h1>

    <div class="login-box">
        <div class="card glass-card my-2 ">
            <div class="card-body">
                <h6 class="login-box-msg text-white ">Por favor, ingrese sus credenciales</h6>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control glass-input @error('email') is-invalid @enderror"
                                name="email" autofocus placeholder="Usuario" value="{{ old('email') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        @error('email')
                            <small class="text-red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password"
                                class="form-control glass-input @error('password') is-invalid @enderror" name="password"
                                placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                   <div class="custom-control custom-switch glass-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                        <label class="custom-control-label text-white" for="remember_me"><small>Acuérdate de mí</small></label>
                    </div>


                    <div class="row ">
                        <div class="col-12  ">
                            <button type="submit" class="btn btn-light btn-block">Iniciar Sesión</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>