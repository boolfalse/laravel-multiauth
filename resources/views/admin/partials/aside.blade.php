<aside class="main-sidebar">
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Management Panel</li>

            <li class="{{ $nav == 'dashboard' ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            @can('for_manager', Auth::user())
                <li class="{{ $nav == 'products' ? 'active' : '' }}"><a href="{{ route('admin.products.index') }}"><i class="fa fa-book"></i> <span>Products</span></a></li>
            @endcan
            @can('for_moderator', Auth::user())
                <li class="{{ $nav == 'users' ? 'active' : '' }}"><a href="{{ route('admin.users.index') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
            @endcan
            @can('for_administrator', Auth::user())
            @endcan
        </ul>
    </section>
</aside>
