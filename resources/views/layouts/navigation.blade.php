<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand ">
    <!-- Brand Logo -->
    <a href="{{ route('admin.index') }}" class="brand-link bg-gradient-navy text-sm">
        <img src="{{ asset('uploads/' . system_info('logo')) }}" alt="Store Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: .8;width: 1.5rem;height: 1.5rem;max-height: unset">
        <span class="brand-text font-weight-light">{{ system_info('short_name') }}</span>
    </a>
    <!-- Sidebar -->
    <div
        class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
        <div class="clearfix"></div>
        <!-- Sidebar Menu -->
        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child"
                data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item dropdown">
                    <a href="{{ route('admin.index') }}" class="nav-link {{ Request::is('admin') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Panel</p>
                    </a>
                </li>
                @can('admin.productos.index')
                    <li class="nav-item dropdown">
                        <a href="{{ route('admin.productos.index')}}"
                            class="nav-link {{ Request::is('admin/productos*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Lista de Productos</p>
                        </a>
                    </li>
                @endcan
                @if (!auth()->user()->hasRole('Administrador'))
                    <li class="nav-item dropdown">
                        <a href="{{ route('admin.sales.form') }}"
                            class="nav-link {{ Request::is(patterns: 'admin/ventas/form') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-plus"></i>
                            <p>Crear Venta</p>
                        </a>
                    </li>
                @endif


                <li class="nav-item dropdown">
                    <a href="{{ route('admin.sales.index') }}"
                        class="nav-link {{ Request::is(patterns: 'admin/ventas*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Ventas</p>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a href="{{ route('admin.reportes.index') }}"
                        class="nav-link {{ Request::is(patterns: 'admin/reportes') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-day"></i>
                        <p>Informe Diario de Ventas</p>
                    </a>
                </li>

                @can('admin.categorias.index')
                    <li class="nav-header">Mantenimiento</li>

                    <li class="nav-item dropdown">
                        <a href="{{ route('admin.categorias.index') }}"
                            class="nav-link {{ Request::is(patterns: 'admin/categorias*') ? 'active' : '' }} ">
                            <i class="nav-icon fas fa-th-list"></i>
                            <p>Lista de Categorias</p>
                        </a>
                    </li>
                @endcan
                @can('admin.usuarios.index')
                    <li class="nav-item dropdown">
                        <a href="{{ route('admin.usuarios.index')}}"
                            class="nav-link  {{ Request::is(patterns: 'admin/usuarios*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Lista de Usuarios</p>
                        </a>
                    </li>
                @endcan
                @can('admin.system.edit')
                    <li class="nav-item dropdown">
                        <a href="{{ route('admin.system.edit') }}"
                            class="nav-link {{ Request::is(patterns: 'admin/system/settings') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tools"></i>
                            <p>Configuración</p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let page = '{{ request()->get('page', 'home') }}';
        let s = '{{ request()->get('s', '') }}';
        page = page.replace(/\//g, '_');
        console.log(page)

        if (document.querySelector(`aside.main-sidebar .nav-link.nav-${page}`)) {
            let link = document.querySelector(`aside.main-sidebar .nav-link.nav-${page}`);
            link.classList.add('active');

            if (link.classList.contains('tree-item')) {
                link.closest('.nav-treeview').previousElementSibling.classList.add('active');
                link.closest('.nav-treeview').parentElement.classList.add('menu-open');
            }

            if (link.classList.contains('nav-is-tree')) {
                link.parentElement.classList.add('menu-open');
            }
        }

        document.querySelectorAll('aside.main-sidebar .nav-link.active').forEach(el => {
            el.classList.add('bg-gradient-navy');
        });
    });
</script>