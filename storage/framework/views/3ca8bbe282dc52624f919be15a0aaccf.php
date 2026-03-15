<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Panel de Control')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Bootstrap 5 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
            
            <style>
                .dashboard-card {
                    transition: all 0.3s ease;
                    border-radius: 15px;
                    border: none;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                }
                
                .dashboard-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                }
                
                .module-card {
                    transition: all 0.3s ease;
                    border-radius: 15px;
                    border: none;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                    cursor: pointer;
                }
                
                .module-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 12px 30px rgba(0,0,0,0.2);
                }
                
                .icon-wrapper {
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    color: white;
                }
                
                .bg-gradient-primary {
                    background: linear-gradient(135deg, #007bff, #0056b3);
                }
                
                .bg-gradient-success {
                    background: linear-gradient(135deg, #28a745, #1e7e34);
                }
                
                .bg-gradient-info {
                    background: linear-gradient(135deg, #17a2b8, #138496);
                }
                
                .bg-gradient-warning {
                    background: linear-gradient(135deg, #ffc107, #e0a800);
                }
                
                .bg-gradient-danger {
                    background: linear-gradient(135deg, #dc3545, #c82333);
                }
                
                .bg-gradient-secondary {
                    background: linear-gradient(135deg, #6c757d, #545b62);
                }
            </style>

            <!-- Header Dashboard -->
            <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="h3 mb-1 text-primary">
                            <i class="bi bi-speedometer2"></i>
                            Panel de Control - Sistema POS Paraguay
                        </h1>
                        <p class="text-muted mb-0">
                            Bienvenido <?php echo e(Auth::user()->name); ?>, gestiona tu negocio desde aquí
                        </p>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi bi-calendar-date"></i>
                            <?php echo e(date('d/m/Y H:i')); ?>

                        </span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Principales -->
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card dashboard-card text-center">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-gradient-success mx-auto mb-3">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <h4 class="text-success fw-bold mb-1">₲ <?php echo e(number_format($stats['revenue_today'] ?? 0)); ?></h4>
                            <p class="text-muted small mb-0">Ingresos Hoy</p>
                            <small class="text-success"><?php echo e($stats['total_sales_today'] ?? 0); ?> ventas</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card dashboard-card text-center">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-gradient-primary mx-auto mb-3">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <h4 class="text-primary fw-bold mb-1"><?php echo e($stats['total_invoices'] ?? 0); ?></h4>
                            <p class="text-muted small mb-0">Total Facturas</p>
                            <small class="text-info">₲ <?php echo e(number_format($stats['revenue_month'] ?? 0)); ?> este mes</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card dashboard-card text-center">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-gradient-warning mx-auto mb-3">
                                <i class="bi bi-boxes"></i>
                            </div>
                            <h4 class="text-warning fw-bold mb-1"><?php echo e($stats['active_products'] ?? 0); ?></h4>
                            <p class="text-muted small mb-0">Productos Activos</p>
                            <?php if(($stats['low_stock_products'] ?? 0) > 0): ?>
                                <small class="text-danger"><?php echo e($stats['low_stock_products']); ?> con stock bajo</small>
                            <?php else: ?>
                                <small class="text-success">Stock suficiente</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card dashboard-card text-center">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-gradient-info mx-auto mb-3">
                                <i class="bi bi-award"></i>
                            </div>
                            <h4 class="text-info fw-bold mb-1"><?php echo e($stats['available_fiscal_stamps'] ?? 0); ?></h4>
                            <p class="text-muted small mb-0">Timbres Disponibles</p>
                            <small class="text-muted">DNIT Paraguay</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Módulos del Sistema -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="card dashboard-card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-grid-3x3-gap"></i>
                                Módulos del Sistema
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Terminal POS -->
                                <div class="col-lg-3 col-md-6">
                                    <a href="<?php echo e(route('pos.index')); ?>" class="text-decoration-none">
                                        <div class="card module-card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="icon-wrapper bg-gradient-primary mx-auto mb-3">
                                                    <i class="bi bi-cash-stack"></i>
                                                </div>
                                                <h5 class="card-title text-dark mb-2">Terminal POS</h5>
                                                <p class="text-muted small mb-0">Sistema de ventas en tiempo real</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Productos -->
                                <div class="col-lg-3 col-md-6">
                                    <a href="<?php echo e(route('products.index')); ?>" class="text-decoration-none">
                                        <div class="card module-card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="icon-wrapper bg-gradient-success mx-auto mb-3">
                                                    <i class="bi bi-box-seam"></i>
                                                </div>
                                                <h5 class="card-title text-dark mb-2">Productos</h5>
                                                <p class="text-muted small mb-0">Gestión de inventario y stock</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Ventas -->
                                <div class="col-lg-3 col-md-6">
                                    <a href="<?php echo e(route('sales.index')); ?>" class="text-decoration-none">
                                        <div class="card module-card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="icon-wrapper bg-gradient-info mx-auto mb-3">
                                                    <i class="bi bi-graph-up"></i>
                                                </div>
                                                <h5 class="card-title text-dark mb-2">Ventas</h5>
                                                <p class="text-muted small mb-0">Historial y reportes</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Facturas -->
                                <div class="col-lg-3 col-md-6">
                                    <a href="<?php echo e(route('invoices.index')); ?>" class="text-decoration-none">
                                        <div class="card module-card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="icon-wrapper bg-gradient-warning mx-auto mb-3">
                                                    <i class="bi bi-receipt"></i>
                                                </div>
                                                <h5 class="card-title text-dark mb-2">Facturas</h5>
                                                <p class="text-muted small mb-0">Facturación fiscal DNIT</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Categorías -->
                                <div class="col-lg-3 col-md-6">
                                    <a href="<?php echo e(route('categories.index')); ?>" class="text-decoration-none">
                                        <div class="card module-card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="icon-wrapper bg-gradient-secondary mx-auto mb-3">
                                                    <i class="bi bi-tags"></i>
                                                </div>
                                                <h5 class="card-title text-dark mb-2">Categorías</h5>
                                                <p class="text-muted small mb-0">Organización de productos</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Inventario -->
                                <div class="col-lg-3 col-md-6">
                                    <a href="<?php echo e(route('inventory.index')); ?>" class="text-decoration-none">
                                        <div class="card module-card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="icon-wrapper bg-gradient-danger mx-auto mb-3">
                                                    <i class="bi bi-boxes"></i>
                                                </div>
                                                <h5 class="card-title text-dark mb-2">Inventario</h5>
                                                <p class="text-muted small mb-0">Control y ajustes de stock</p>
                                                <?php if(($stats['low_stock_products'] ?? 0) > 0): ?>
                                                    <small class="text-danger">
                                                        <i class="bi bi-exclamation-triangle"></i>
                                                        <?php echo e($stats['low_stock_products']); ?> productos con stock bajo
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Timbres Fiscales -->
                                <div class="col-lg-3 col-md-6">
                                    <a href="<?php echo e(route('fiscal-stamps.index')); ?>" class="text-decoration-none">
                                        <div class="card module-card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="icon-wrapper bg-gradient-danger mx-auto mb-3">
                                                    <i class="bi bi-award"></i>
                                                </div>
                                                <h5 class="card-title text-dark mb-2">Timbres Fiscales</h5>
                                                <p class="text-muted small mb-0">Control de timbres DNIT</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Reportes -->
                                <div class="col-lg-3 col-md-6">
                                    <a href="<?php echo e(route('sales.reports')); ?>" class="text-decoration-none">
                                        <div class="card module-card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="icon-wrapper bg-gradient-primary mx-auto mb-3">
                                                    <i class="bi bi-bar-chart"></i>
                                                </div>
                                                <h5 class="card-title text-dark mb-2">Reportes</h5>
                                                <p class="text-muted small mb-0">Análisis y estadísticas</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Configuración -->
                                <div class="col-lg-3 col-md-6">
                                    <a href="<?php echo e(route('settings.invoice')); ?>" class="text-decoration-none">
                                        <div class="card module-card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="icon-wrapper bg-gradient-secondary mx-auto mb-3">
                                                    <i class="bi bi-gear"></i>
                                                </div>
                                                <h5 class="card-title text-dark mb-2">Configuración</h5>
                                                <p class="text-muted small mb-0">Ajustes del sistema</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="row g-4">
                <!-- Productos con Stock Bajo -->
                <div class="col-lg-6">
                    <div class="card dashboard-card h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-exclamation-triangle text-warning"></i>
                                Stock Bajo
                            </h5>
                            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-sm btn-outline-warning">Ver Todos</a>
                        </div>
                        <div class="card-body">
                            <?php if(isset($lowStockProducts) && $lowStockProducts->count() > 0): ?>
                                <?php $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <div>
                                            <strong class="text-dark"><?php echo e($product->name); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo e($product->code); ?></small>
                                        </div>
                                        <span class="badge bg-warning text-dark"><?php echo e($product->stock); ?> unidades</span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">¡Todos los productos tienen stock suficiente!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Ventas Recientes -->
                <div class="col-lg-6">
                    <div class="card dashboard-card h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-clock-history text-primary"></i>
                                Ventas Recientes
                            </h5>
                            <a href="<?php echo e(route('sales.index')); ?>" class="btn btn-sm btn-outline-primary">Ver Todas</a>
                        </div>
                        <div class="card-body">
                            <?php if(isset($recentSales) && $recentSales->count() > 0): ?>
                                <?php $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <div>
                                            <strong class="text-dark">Venta #<?php echo e($sale->id); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?php echo e($sale->created_at->format('d/m/Y H:i')); ?> - <?php echo e($sale->user->name ?? 'N/A'); ?>

                                            </small>
                                        </div>
                                        <span class="badge bg-success">₲ <?php echo e(number_format($sale->total_amount)); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-cart-x" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">No hay ventas registradas aún</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap 5 JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/dashboard.blade.php ENDPATH**/ ?>