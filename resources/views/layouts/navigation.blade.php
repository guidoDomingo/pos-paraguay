<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <!-- Logo/Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <i class="bi bi-shop me-2"></i>
            Sistema POS Paraguay
        </a>

        <!-- Mobile toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                        <i class="bi bi-cash-stack me-1"></i>
                        Terminal POS
                    </a>
                </li>
                @if(Route::has('cash.current'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cash.*') ? 'active' : '' }}" href="{{ route('cash.current') }}">
                        <i class="bi bi-cash-coin me-1"></i>
                        Caja
                    </a>
                </li>
                @endif
                @if(Route::has('products.index'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-box-seam me-1"></i>
                        Productos
                    </a>
                </li>
                @endif
                @if(Route::has('categories.index'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="bi bi-tags me-1"></i>
                        Categorías
                    </a>
                </li>
                @endif
                @if(Route::has('inventory.index'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                        <i class="bi bi-boxes me-1"></i>
                        Inventario
                    </a>
                </li>
                @endif
                @if(Route::has('sales.index'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                        <i class="bi bi-graph-up me-1"></i>
                        Ventas
                    </a>
                </li>
                @endif
                @if(Route::has('admin.data-management.index') && auth()->user() && auth()->user()->hasPermission('admin.settings'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.data-management.*') ? 'active' : '' }}" href="{{ route('admin.data-management.index') }}">
                        <i class="bi bi-gear-fill me-1"></i>
                        Gestión de Datos
                    </a>
                </li>
                @endif
            </ul>

            <!-- User menu -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </h6>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i class="bi bi-box-arrow-right me-1"></i>
                                Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Script para solucionar conflictos de dropdown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Solución manual para dropdown si Bootstrap tiene conflictos
    const dropdownToggle = document.getElementById('navbarDropdown');
    const dropdownMenu = dropdownToggle.nextElementSibling;
    
    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle la clase 'show'
            dropdownMenu.classList.toggle('show');
            
            // Toggle aria-expanded
            const expanded = dropdownToggle.getAttribute('aria-expanded') === 'true';
            dropdownToggle.setAttribute('aria-expanded', !expanded);
        });
        
        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
                dropdownToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
</script>

<!-- Modal de Confirmación de Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Confirmar Cerrar Sesión
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Está seguro que desea cerrar sesión?</p>
                <small class="text-muted">Será redirigido a la página de login.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>
                    Cancelar
                </button>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>