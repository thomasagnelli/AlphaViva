<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-10">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
    <img src="/admin-lte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

    @auth    
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img src="/admin-lte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
            <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>
    @endauth

    @auth
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="/" class="nav-link active">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="/unidades" class="nav-link">
                    <i class="nav-icon fab fa-buromobelexperte"></i>
                    <p>Unidades</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="/clientes" class="nav-link">
                    <i class="nav-icon fas fa-mug-hot"></i>
                    <p>Clientes</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="/usuarios" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Usuários</p>
                </a>
            </li>

            <!--
            <li class="nav-item menu-ope">
                <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="/" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Mapa de Vendas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/reservas" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Reservas</p>
                    </a>
                </li>
                </ul>
            </li>
            -->
            
        </ul>
    </nav>
    @endauth
    
    <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>