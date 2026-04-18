<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <!-- Logo/Brand -->
        <a class="navbar-brand fw-bold" href="<?php echo e(route('dashboard')); ?>">
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
                    <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                        <i class="bi bi-speedometer2 me-1"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pos.*') ? 'active' : ''); ?>" href="<?php echo e(route('pos.index')); ?>">
                        <i class="bi bi-cash-stack me-1"></i>
                        Terminal POS
                    </a>
                </li>
                <?php if(Route::has('cash.current')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('cash.*') ? 'active' : ''); ?>" href="<?php echo e(route('cash.current')); ?>">
                        <i class="bi bi-cash-coin me-1"></i>
                        Caja
                    </a>
                </li>
                <?php endif; ?>
                <?php if(Route::has('products.index')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>" href="<?php echo e(route('products.index')); ?>">
                        <i class="bi bi-box-seam me-1"></i>
                        Productos
                    </a>
                </li>
                <?php endif; ?>
                <?php if(Route::has('categories.index')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>" href="<?php echo e(route('categories.index')); ?>">
                        <i class="bi bi-tags me-1"></i>
                        Categorías
                    </a>
                </li>
                <?php endif; ?>
                <?php if(Route::has('inventory.index')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('inventory.*') ? 'active' : ''); ?>" href="<?php echo e(route('inventory.index')); ?>">
                        <i class="bi bi-boxes me-1"></i>
                        Inventario
                    </a>
                </li>
                <?php endif; ?>
                <?php if(Route::has('sales.index')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('sales.*') ? 'active' : ''); ?>" href="<?php echo e(route('sales.index')); ?>">
                        <i class="bi bi-graph-up me-1"></i>
                        Ventas
                    </a>
                </li>
                <?php endif; ?>
                <?php if(auth()->user() && auth()->user()->hasPermission('admin.users')): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e(request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? 'active' : ''); ?>"
                        href="#" role="button" aria-expanded="false">
                        <i class="bi bi-shield-lock me-1"></i>Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('admin.users.index')); ?>">
                            <i class="bi bi-people me-2"></i>Usuarios
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('admin.roles.index')); ?>">
                            <i class="bi bi-shield-lock me-2"></i>Roles
                        </a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(Route::has('admin.data-management.index') && auth()->user() && auth()->user()->hasPermission('admin.settings')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('admin.data-management.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.data-management.index')); ?>">
                        <i class="bi bi-gear-fill me-1"></i>
                        Gestión de Datos
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- User menu -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" aria-expanded="false">
                        <i class="bi bi-person-circle me-2"></i>
                        <?php echo e(Auth::user()->name); ?>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">
                                <small class="text-muted"><?php echo e(Auth::user()->email); ?></small>
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
    // Manejo manual de todos los dropdowns de la navbar
    document.querySelectorAll('.navbar .dropdown-toggle').forEach(function(toggle) {
        const menu = toggle.nextElementSibling;
        if (!menu) return;

        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Cerrar otros dropdowns abiertos
            document.querySelectorAll('.navbar .dropdown-menu.show').forEach(function(m) {
                if (m !== menu) {
                    m.classList.remove('show');
                    m.previousElementSibling.setAttribute('aria-expanded', 'false');
                }
            });

            menu.classList.toggle('show');
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', !expanded);
        });
    });

    // Cerrar todos al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.navbar .dropdown')) {
            document.querySelectorAll('.navbar .dropdown-menu.show').forEach(function(m) {
                m.classList.remove('show');
                m.previousElementSibling.setAttribute('aria-expanded', 'false');
            });
        }
    });
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
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\bodega-app\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>