<aside class="main-sidebar">
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            @php($admin_role = Auth::user()->getRoleNames()->first())
            <li class="header">{{ $admin_role }}</li>

            <li class="{{ $nav == 'dashboard' ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            {{--//ss roles in "config/project.admin.roles"--}}
            @if($admin_role == 'administrator' || $admin_role == 'moderator')
                <li class="{{ $nav == 'users' ? 'active' : '' }}"><a href="{{ route('admin.users.index') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
            @endif
        </ul>
    </section>
</aside>