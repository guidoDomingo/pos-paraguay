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
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
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
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-1"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav><?php /**PATH C:\laragon\www\bodega-app\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>