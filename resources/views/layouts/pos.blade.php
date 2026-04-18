<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema POS Paraguay')</title>
    <!-- QZ Tray (impresión directa en Windows) -->
    <script src="https://cdn.qz.io/qz-tray/2.2.4/qz-tray.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom POS Styles -->
    <style>
        :root {
            --pos-primary: #0d6efd;
            --pos-secondary: #6c757d;
            --pos-success: #198754;
            --pos-danger: #dc3545;
            --pos-warning: #ffc107;
            --pos-info: #0dcaf0;
            --pos-dark: #212529;
            --pos-light: #f8f9fa;
        }
        
        body {
            background-color: var(--pos-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .pos-header {
            background: linear-gradient(135deg, var(--pos-primary), #0056b3);
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .pos-nav-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
            text-decoration: none;
        }
        
        .pos-nav-brand:hover {
            color: white !important;
            text-shadow: 0 0 10px rgba(255,255,255,0.3);
        }
        
        .nav-link {
            color: rgba(255,255,255,0.7) !important;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white !important;
            background-color: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }
        
        .hover-white:hover {
            color: white !important;
        }
        
        .pos-nav-info {
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.2s ease-in-out;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .btn-pos {
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-pos:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        .badge {
            font-size: 0.8em;
            padding: 0.5em 0.8em;
        }
        
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* POS Terminal Specific Styles */
        .pos-sidebar {
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 15px;
        }
        
        .pos-main {
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 15px;
        }
        
        .product-grid {
            max-height: 60vh;
            overflow-y: auto;
        }
        
        .product-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: var(--pos-primary);
        }
        
        .cart-item {
            border-radius: 10px;
            border: 1px solid #e9ecef;
            background: #f8f9fa;
            margin-bottom: 10px;
        }
        
        .search-input {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            padding: 12px 20px 12px 45px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: var(--pos-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .total-section {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            border: 2px solid #dee2e6;
        }
        
        .total-final {
            background: linear-gradient(135deg, var(--pos-success), #157347);
            color: white;
            border-radius: 10px;
        }
    </style>
    @livewireStyles
</head>
<body>
    <!-- Header Navigation -->
    <nav class="pos-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ route('pos.index') }}" class="pos-nav-brand me-4">
                        <i class="bi bi-shop"></i>
                        Terminal POS Paraguay
                    </a>
                    <!-- Navigation Menu -->
                    <div class="d-none d-lg-flex align-items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="nav-link text-white-50 hover-white">
                            <i class="bi bi-house-door"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('pos.index') }}" class="nav-link text-white hover-white">
                            <i class="bi bi-cash-register"></i>
                            Terminal POS
                        </a>
                        <a href="{{ route('products.index') }}" class="nav-link text-white-50 hover-white">
                            <i class="bi bi-box-seam"></i>
                            Productos
                        </a>
                        <a href="{{ route('categories.index') }}" class="nav-link text-white-50 hover-white">
                            <i class="bi bi-tags"></i>
                            Categorías
                        </a>
                        <a href="{{ route('inventory.index') }}" class="nav-link text-white-50 hover-white">
                            <i class="bi bi-boxes"></i>
                            Inventario
                        </a>
                        <a href="{{ route('sales.index') }}" class="nav-link text-white-50 hover-white">
                            <i class="bi bi-graph-up"></i>
                            Ventas
                        </a>
                        <a href="{{ route('settings.invoice') }}" class="nav-link text-white-50 hover-white">
                            <i class="bi bi-gear-fill"></i>
                            Configuración
                        </a>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <!-- Mobile Menu Button -->
                    <button class="btn btn-link text-white d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" aria-expanded="false">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    
                    <div class="pos-nav-info d-none d-md-flex">
                        <i class="bi bi-person-circle"></i>
                        <span class="ms-1">{{ Auth::user()->name ?? 'Administrador Sistema' }}</span>
                    </div>
                    <div class="pos-nav-info d-none d-md-flex">
                        @php $openCaja = \App\Models\CashRegister::getOpenRegister(Auth::user()->company_id); @endphp
                        @if($openCaja)
                        <a href="{{ route('cash.current') }}" class="text-decoration-none text-white d-flex align-items-center gap-1">
                            <i class="bi bi-cash-coin text-success"></i>
                            <span class="ms-1">Caja #{{ $openCaja->id }}</span>
                        </a>
                        @else
                        <a href="{{ route('cash.open') }}" class="text-decoration-none text-warning d-flex align-items-center gap-1">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span class="ms-1">Abrir caja</span>
                        </a>
                        @endif
                    </div>
                    <div class="pos-nav-info">
                        <i class="bi bi-calendar3"></i>
                        <span class="ms-1">{{ date('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div class="collapse mt-3" id="mobileNav">
                <div class="d-flex flex-column gap-2 pb-3">
                    <a href="{{ route('dashboard') }}" class="nav-link text-white-50 hover-white">
                        <i class="bi bi-house-door"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('pos.index') }}" class="nav-link text-white hover-white">
                        <i class="bi bi-cash-register"></i>
                        Terminal POS
                    </a>
                    <a href="{{ route('products.index') }}" class="nav-link text-white-50 hover-white">
                        <i class="bi bi-box-seam"></i>
                        Productos
                    </a>
                    <a href="{{ route('categories.index') }}" class="nav-link text-white-50 hover-white">
                        <i class="bi bi-tags"></i>
                        Categorías
                    </a>
                    <a href="{{ route('inventory.index') }}" class="nav-link text-white-50 hover-white">
                        <i class="bi bi-boxes"></i>
                        Inventario
                    </a>
                    <a href="{{ route('sales.index') }}" class="nav-link text-white-50 hover-white">
                        <i class="bi bi-graph-up"></i>
                        Ventas
                    </a>
                    <a href="{{ route('settings.invoice') }}" class="nav-link text-white-50 hover-white">
                        <i class="bi bi-gear-fill"></i>
                        Configuración
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Flash Messages -->
    @if (session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Highlight active navigation
            const currentUrl = window.location.href;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.href === currentUrl) {
                    link.classList.add('active');
                    link.classList.remove('text-white-50');
                    link.classList.add('text-white');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>