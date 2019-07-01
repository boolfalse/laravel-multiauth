<!DOCTYPE html>
<html>
<head>
    @include('admin.partials.head')
    @yield('custom_styles')
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
    @include('admin.partials.header')
    @include('admin.partials.aside')

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

        @include('admin.partials.flashes')

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Dashboard <small>{{ $nav }}</small>
                    @if($nav != 'dashboard' && $action != 'create' && $action != 'no_add')
                        <a class="btn btn-success" href="{{ route('admin.' . $nav . '.create') }}">
                            <i class="fa fa-plus"></i> Add
                        </a>
                    @endif
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

    @include('admin.partials.footer')
</div>

@include('admin.partials.scripts')

@yield('custom_scripts')

</body>
</html>
