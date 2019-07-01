<header class="main-header">
    <!-- Logo -->
    <a target="_blank" href="{{ route('front') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        {{-- //ss TODO: App Names like this, we can do using these methods https://stackoverflow.com/questions/6572974/regex-to-break-up-camelcase-string-php/6572999#6572999 --}}
        <span class="logo-mini"><b>M</b>A</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Multi</b> Auth</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user-circle"></i>
                        <span class="hidden-xs">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                                <a class="btn btn-default btn-flat" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>