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
    <div class="py-4">
        <div class="container-fluid px-4">

            <style>
                .stats-card {
                    border-radius: 14px;
                    padding: 1.5rem;
                    color: white;
                    box-shadow: 0 6px 20px rgba(0,0,0,.12);
                    transition: transform .25s;
                }
                .stats-card:hover { transform: translateY(-4px); }
                .stats-card .icon-wrap {
                    width: 52px; height: 52px; border-radius: 50%;
                    background: rgba(255,255,255,.2);
                    display: flex; align-items: center; justify-content: center;
                    font-size: 1.4rem;
                }
                .chart-card {
                    border-radius: 14px;
                    box-shadow: 0 4px 15px rgba(0,0,0,.07);
                    border: none;
                }
                .section-title {
                    font-size: 1rem; font-weight: 700; color: #374151;
                    border-bottom: 3px solid #667eea; padding-bottom: .4rem; margin-bottom: 1rem;
                }
                .data-table thead tr th {
                    background: linear-gradient(135deg,#667eea,#764ba2) !important;
                    color: #fff !important; font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;
                }
                .page-hero {
                    background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
                    border-radius: 14px; color: white; padding: 1.5rem 2rem; margin-bottom: 1.5rem;
                }
            </style>

            
            <div class="page-hero d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-2"></i>Reportes de Ventas</h2>
                    <p class="mb-0 opacity-75 small">Período: <?php echo e(\Carbon\Carbon::parse($startDate)->format('d/m/Y')); ?> — <?php echo e(\Carbon\Carbon::parse($endDate)->format('d/m/Y')); ?></p>
                </div>
                <a href="<?php echo e(route('sales.index')); ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>

            
            <div class="card chart-card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title"><i class="bi bi-funnel me-2"></i>Filtros</h5>
                    <form method="GET" action="<?php echo e(route('sales.reports')); ?>">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Fecha Inicio</label>
                                <input type="date" name="start_date" value="<?php echo e($startDate); ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Fecha Fin</label>
                                <input type="date" name="end_date" value="<?php echo e($endDate); ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Vendedor</label>
                                <select name="user_id" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e($userId == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-search me-1"></i>Generar Reporte
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-2">
                    <div class="stats-card" style="background:linear-gradient(135deg,#667eea,#764ba2)">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small opacity-75 mb-1 text-uppercase" style="font-size:.7rem;letter-spacing:1px;">Total Ventas</div>
                                <div class="fw-bold" style="font-size:1.3rem;">₲ <?php echo e(number_format($totalSales, 0, ',', '.')); ?></div>
                            </div>
                            <div class="icon-wrap"><i class="bi bi-currency-dollar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="stats-card" style="background:linear-gradient(135deg,#f093fb,#f5576c)">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small opacity-75 mb-1 text-uppercase" style="font-size:.7rem;letter-spacing:1px;">Cantidad</div>
                                <div class="fw-bold" style="font-size:1.3rem;"><?php echo e(number_format($salesCount)); ?></div>
                                <div class="small opacity-75">ventas</div>
                            </div>
                            <div class="icon-wrap"><i class="bi bi-cart-check"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="stats-card" style="background:linear-gradient(135deg,#4facfe,#00f2fe)">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small opacity-75 mb-1 text-uppercase" style="font-size:.7rem;letter-spacing:1px;">Venta Promedio</div>
                                <div class="fw-bold" style="font-size:1.3rem;">₲ <?php echo e(number_format($averageSale, 0, ',', '.')); ?></div>
                            </div>
                            <div class="icon-wrap"><i class="bi bi-graph-up"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="stats-card" style="background:linear-gradient(135deg,#43e97b,#38f9d7)">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small opacity-75 mb-1 text-uppercase" style="font-size:.7rem;letter-spacing:1px;">Productos Vendidos</div>
                                <div class="fw-bold" style="font-size:1.3rem;"><?php echo e(number_format($itemsSold ?? 0)); ?></div>
                                <div class="small opacity-75">unidades</div>
                            </div>
                            <div class="icon-wrap"><i class="bi bi-box"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="stats-card" style="background:linear-gradient(135deg,#f7971e,#ffd200)">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small opacity-75 mb-1 text-uppercase" style="font-size:.7rem;letter-spacing:1px;">Ganancia Total</div>
                                <div class="fw-bold" style="font-size:1.3rem;">₲ <?php echo e(number_format($totalProfit, 0, ',', '.')); ?></div>
                                <div class="small opacity-75">Costo: ₲ <?php echo e(number_format($totalCost, 0, ',', '.')); ?></div>
                            </div>
                            <div class="icon-wrap"><i class="bi bi-graph-up-arrow"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="stats-card" style="background:linear-gradient(135deg,#11998e,#38ef7d)">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small opacity-75 mb-1 text-uppercase" style="font-size:.7rem;letter-spacing:1px;">Margen</div>
                                <div class="fw-bold" style="font-size:1.3rem;"><?php echo e($profitMargin); ?>%</div>
                                <div class="small opacity-75">rentabilidad</div>
                            </div>
                            <div class="icon-wrap"><i class="bi bi-percent"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card chart-card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title"><i class="bi bi-bar-chart me-2"></i>Ventas por Día</h5>
                    <?php if($salesByDay->count() > 0): ?>
                        <canvas id="chartByDay" height="90"></canvas>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-bar-chart" style="font-size:3rem;"></i>
                            <p class="mt-2 mb-0">Sin datos para el período seleccionado</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card chart-card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title"><i class="bi bi-trophy me-2"></i>Top Productos — Rentabilidad</h5>
                    <?php if($topProducts->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table data-table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th class="text-end">Unidades</th>
                                    <th class="text-end">Ingresos</th>
                                    <th class="text-end">Ganancia</th>
                                    <th class="text-end">Margen</th>
                                    <th style="min-width:120px;">Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $margin = $prod->total_revenue > 0 ? round(($prod->total_profit / $prod->total_revenue) * 100, 1) : 0;
                                    $barColor = $margin >= 30 ? '#38ef7d' : ($margin >= 15 ? '#ffd200' : '#f5576c');
                                ?>
                                <tr>
                                    <td class="text-muted small"><?php echo e($i + 1); ?></td>
                                    <td class="fw-semibold"><?php echo e($prod->product_name); ?></td>
                                    <td class="text-end"><?php echo e(number_format($prod->total_quantity, 0)); ?></td>
                                    <td class="text-end">₲ <?php echo e(number_format($prod->total_revenue, 0, ',', '.')); ?></td>
                                    <td class="text-end fw-bold <?php echo e($prod->total_profit >= 0 ? 'text-success' : 'text-danger'); ?>">
                                        ₲ <?php echo e(number_format($prod->total_profit, 0, ',', '.')); ?>

                                    </td>
                                    <td class="text-end fw-bold"><?php echo e($margin); ?>%</td>
                                    <td>
                                        <div class="progress" style="height:8px;border-radius:4px;">
                                            <div class="progress-bar" style="width:<?php echo e(min(100, max(0, $margin))); ?>%;background:<?php echo e($barColor); ?>;"></div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size:3rem;"></i>
                            <p class="mt-2 mb-0">Sin datos</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="row g-4 mb-4">
                
                <div class="col-lg-6">
                    <div class="card chart-card h-100">
                        <div class="card-body p-4">
                            <h5 class="section-title"><i class="bi bi-bar-chart-steps me-2"></i>Ingresos vs Ganancia</h5>
                            <?php if($topProducts->count() > 0): ?>
                                <canvas id="chartTopProducts" height="220"></canvas>
                            <?php else: ?>
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-inbox" style="font-size:3rem;"></i>
                                    <p class="mt-2 mb-0">Sin datos</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="col-lg-6">
                    <div class="card chart-card h-100">
                        <div class="card-body p-4">
                            <h5 class="section-title"><i class="bi bi-people me-2"></i>Ventas por Vendedor</h5>
                            <?php if($salesByUser->count() > 0): ?>
                                <canvas id="chartByUser" height="220"></canvas>
                            <?php else: ?>
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-person-x" style="font-size:3rem;"></i>
                                    <p class="mt-2 mb-0">Sin datos</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card chart-card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title"><i class="bi bi-table me-2"></i>Detalle de Ventas</h5>
                    <div class="table-responsive">
                        <table class="table data-table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th class="text-end">Total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $salesDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="small"><?php echo e($sale->created_at->format('d/m/Y H:i')); ?></td>
                                    <td class="fw-semibold"><?php echo e($sale->invoice_number ?: '-'); ?></td>
                                    <td><?php echo e($sale->customer_name ?: 'Cliente general'); ?></td>
                                    <td><?php echo e($sale->user->name ?? 'N/A'); ?></td>
                                    <td class="fw-bold text-success text-end">₲ <?php echo e(number_format($sale->total_amount, 0, ',', '.')); ?></td>
                                    <td>
                                        <?php if(strtoupper($sale->status) === 'COMPLETED'): ?>
                                            <span class="badge bg-success">Completada</span>
                                        <?php elseif(strtoupper($sale->status) === 'PENDING'): ?>
                                            <span class="badge bg-warning text-dark">Pendiente</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo e($sale->status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x" style="font-size:2.5rem;"></i>
                                        <p class="mt-2 mb-0">No hay ventas en el período seleccionado</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        Chart.defaults.font.family = "'Inter','Segoe UI',Arial,sans-serif";
        Chart.defaults.color = '#6b7280';

        const fmt = v => '₲ ' + new Intl.NumberFormat('es-PY').format(v);
        const COLORS = ['#667eea','#f5576c','#4facfe','#43e97b','#fa709a','#fee140','#764ba2','#f093fb','#00f2fe','#38f9d7'];

        <?php if($salesByDay->count() > 0): ?>
        new Chart(document.getElementById('chartByDay'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartDayLabels, 15, 512) ?>,
                datasets: [
                    {
                        label: 'Total (₲)',
                        data: <?php echo json_encode($chartDayTotals, 15, 512) ?>,
                        backgroundColor: 'rgba(102,126,234,.75)',
                        borderColor: '#667eea',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Cantidad de ventas',
                        data: <?php echo json_encode($chartDayCounts, 15, 512) ?>,
                        type: 'line',
                        borderColor: '#f5576c',
                        backgroundColor: 'rgba(245,87,108,.1)',
                        borderWidth: 2,
                        pointRadius: 5,
                        tension: .4,
                        yAxisID: 'y2',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { callbacks: { label: ctx => ctx.datasetIndex === 0 ? ' ' + fmt(ctx.parsed.y) : ' ' + ctx.parsed.y + ' ventas' } }
                },
                scales: {
                    y:  { position: 'left',  ticks: { callback: v => fmt(v) } },
                    y2: { position: 'right', grid: { drawOnChartArea: false }, ticks: { stepSize: 1 } }
                }
            }
        });
        <?php endif; ?>

        <?php if($topProducts->count() > 0): ?>
        new Chart(document.getElementById('chartTopProducts'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartProdLabels, 15, 512) ?>,
                datasets: [
                    {
                        label: 'Ingresos',
                        data: <?php echo json_encode($chartProdRevenue, 15, 512) ?>,
                        backgroundColor: 'rgba(102,126,234,.8)',
                        borderRadius: 4,
                    },
                    {
                        label: 'Ganancia',
                        data: <?php echo json_encode($chartProdProfit, 15, 512) ?>,
                        backgroundColor: 'rgba(56,239,125,.8)',
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { callbacks: { label: ctx => ' ' + ctx.dataset.label + ': ' + fmt(ctx.parsed.x) } }
                },
                scales: { x: { ticks: { callback: v => fmt(v) } } }
            }
        });
        <?php endif; ?>

        <?php if($salesByUser->count() > 0): ?>
        new Chart(document.getElementById('chartByUser'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($chartUserLabels, 15, 512) ?>,
                datasets: [{ data: <?php echo json_encode($chartUserTotals, 15, 512) ?>, backgroundColor: COLORS, borderWidth: 2, hoverOffset: 8 }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + fmt(ctx.parsed) } }
                }
            }
        });
        <?php endif; ?>
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\bodega-app\resources\views/sales/reports.blade.php ENDPATH**/ ?>