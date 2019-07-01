<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} | Log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap 4.0.0 -->
    <link rel="stylesheet" href="{{ asset('css/admin/bootstrap-4.0.0.min.css') }}">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Theme style -->
    <link href="{{ asset('css/admin/admin.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/admin/style.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body class="login-page" style="background-image: url({{ asset('images/admin.jpg') }})">
<div class="login-box">
    <div class="login-logo">
        <p><b>Multi</b> Auth</p>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="{{ route('admin.login') }}" method="post">
            {{ csrf_field() }}

            <div class="form-group has-feedback">
                <input type="text" name="email" class="form-control" placeholder="Email"/>
                <i class="fa fa-envelope form-control-feedback"></i>
                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Password"/>
                <i class="fa fa-lock fa-fw"></i>
                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Remember Me
                        </label>
                    </div>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>
        </form>

    </div>
</div>

<!-- Optional JavaScript -->
<script src="{{ asset('js/admin/jquery-3.3.1.slim.min.js') }}"></script>
<script src="{{ asset('js/admin/bootstrap-4.1.1.min.js') }}"></script>

<!-- iCheck -->
<script src="{{ asset('js/admin/admin.min.js') }}" type="text/javascript"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>

</body>
</html>