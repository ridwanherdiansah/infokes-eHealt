<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">eHealth<sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ $type_menu === 'dashboard' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    @if(auth()->user()->status == 1)
    <!-- Heading -->
    <div class="sidebar-heading">
        Users
    </div>

    <!-- Nav Item - Charts -->
    <li class="nav-item {{ $type_menu === 'users' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/users') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Users</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    @endif

    <?php
        $role_id = auth()->user()->id;

        
        $menus = DB::table('menus')
                ->join('users_access_menus', 'menus.id', '=', 'users_access_menus.menu_id')
                ->where('users_access_menus.role_id', $role_id)
                ->orderBy('users_access_menus.menu_id', 'ASC')
                ->get();

        if ($menus) {
            foreach ($menus as $menu) {
                $subMenu = DB::table('sub_menus')
                        ->join('menus', 'menus.id', '=', 'sub_menus.menu_id')
                        ->where('sub_menus.menu_id', $menu->menu_id)
                        ->where('sub_menus.status', 1)
                        ->get();

                    echo'<div class="sidebar-heading">'. $menu->menu .'</div>';

                    foreach ($subMenu as $item) {
                        echo'<li class="nav-item ' . ($type_menu === $item->type_menu ? 'active' : '') . '">
                                <a class="nav-link" href="'. ($item->url ? $item->url : '') .'">
                                    '. $item->icon .'
                                    <span>' . $item->nama . '</span></a>
                            </li>';
                    }
            }
        }
    ?>

    <!-- Heading -->
    

    <!-- Nav Item - Charts -->
    

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>