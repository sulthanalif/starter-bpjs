<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - {{ env('APP_NAME', 'Laravel') }}</title>

  {{-- <link href="{{ asset('assets/dist/img/icon-login.png')}}" rel="icon">
  <link href="{{ asset('assets/dist/img/icon-login.png')}}" rel="apple-touch-icon"> --}}
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
  <style>
    .img-logo {
        padding: 0.25rem;
        background-color: transparent;
        border: transparent;
        border-radius: 0.25rem;
        max-width: 100%;
        height: auto;
    }
  </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
    <!-- /.login-logo -->
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        <div class="login-logo">
            <img src="#" alt="" class="img-logo">
        </div>

    <div class="card card-outline card-primary">
        <div class="card-body">
        <p class="login-box-msg">Login</p>

        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="email" class="form-control  @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" autofocus autocomplete="off" value="{{ old('email') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password" autofocus autocomplete="off">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span toggle="#password-field" class="fa fa-eye-slash field_icon toggle-password" role="button"></span>
                    </div>
                </div>
                @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                @enderror
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    </div>
<!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
    <script>
        $("body").on('click', '.toggle-password', function() {
            $(this).toggleClass("fa-eye-slash fa-eye");
            var passInput=$("#password");
            if(passInput.attr('type')==='password')
            {
                passInput.attr('type','text');
            }else{
                passInput.attr('type','password');
            }
        })
    </script>
</body>
</html>
