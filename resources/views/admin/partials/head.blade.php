<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ config('app.name') }} | {{ $nav }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

{{--<!-- Bootstrap 3.3.7 -->--}}
{{--<link rel="stylesheet" href="{{ asset('css/admin/bootstrap.min.css') }}">--}}
{{--<!-- Font Awesome -->--}}
{{--<link rel="stylesheet" href="{{ asset('css/admin/font-awesome.min.css') }}">--}}
{{--<!-- Ionicons -->--}}
{{--<link rel="stylesheet" href="{{ asset('css/admin/ionicons.min.css') }}">--}}
{{--<!-- Theme style -->--}}
{{--<link rel="stylesheet" href="{{ asset('css/admin/BoolFalse.min.css') }}">--}}
{{--<!-- BoolFalse Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->--}}
{{--<link rel="stylesheet" href="{{ asset('css/admin/_all-skins.min.css') }}">--}}
{{--<!-- bootstrap wysihtml5 - text editor -->--}}
{{--<link rel="stylesheet" href="{{ asset('css/admin/bootstrap3-wysihtml5.min.css') }}">--}}
{{--<!-- Select2 -->--}}
{{--<link rel="stylesheet" href="{{ asset('css/admin/select2.min.css') }}">--}}
{{--<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->--}}
{{--<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->--}}
{{--<!--[if lt IE 9]>--}}
{{--<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>--}}
{{--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>--}}
{{--<![endif]-->--}}
{{--<!-- Google Font -->--}}
{{--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">--}}

<link rel="stylesheet" href="{{ mix('css/dashboard.css') }}" />
