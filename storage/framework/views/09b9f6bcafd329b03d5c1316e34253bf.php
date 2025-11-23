<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema POS Paraguay</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .login-card {
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        
        .login-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 20px 20px 0 0;
            color: white;
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,123,255,0.3);
            color: white;
        }
        
        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
        
        .forgot-password {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-password:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        
        .logo-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .system-info {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid h-100 d-flex align-items-center justify-content-center py-4">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card login-card">
                    <!-- Header -->
                    <div class="login-header text-center">
                        <div class="logo-icon">
                            <i class="bi bi-shop"></i>
                        </div>
                        <h2 class="h3 mb-2 fw-bold">Sistema POS Paraguay</h2>
                        <p class="mb-0 opacity-75">Inicie sesión para continuar</p>
                    </div>

                    <!-- Form Body -->
                    <div class="card-body p-4">
                        <!-- Session Status -->
                        <?php if(session('status')): ?>
                        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div><?php echo e(session('status')); ?></div>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('login')); ?>">
                            <?php echo csrf_field(); ?>

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-1"></i> Email
                                </label>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="<?php echo e(old('email')); ?>" 
                                    required 
                                    autofocus
                                    class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="usuario@empresa.com"
                                >
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i><?php echo e($message); ?>

                                </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-1"></i> Contraseña
                                </label>
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    required
                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Ingrese su contraseña"
                                >
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i><?php echo e($message); ?>

                                </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Remember Me -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Recordarme
                                    </label>
                                </div>

                                <?php if(Route::has('password.request')): ?>
                                <a href="<?php echo e(route('password.request')); ?>" class="forgot-password">
                                    ¿Olvidó su contraseña?
                                </a>
                                <?php endif; ?>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-login w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Iniciar Sesión
                            </button>
                        </form>

                        <!-- System Info -->
                        <div class="system-info text-center">
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-shield-check text-success me-1"></i>
                                        <strong>DNIT</strong><br>
                                        Compatible
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-geo-alt text-primary me-1"></i>
                                        <strong>Paraguay</strong><br>
                                        Certificado
                                    </small>
                                </div>
                            </div>
                            <hr class="my-3">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Sistema POS para comercios en Paraguay
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Demo Credentials -->
                <div class="mt-3 text-center">
                    <div class="card border-0" style="background: rgba(255,255,255,0.9); border-radius: 15px;">
                        <div class="card-body py-3">
                            <h6 class="text-dark mb-2">
                                <i class="bi bi-key text-warning me-1"></i>
                                Credenciales de Prueba
                            </h6>
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <small class="d-block">
                                        <strong>Email:</strong><br>
                                        <code>admin@bodegaapp.com</code>
                                    </small>
                                </div>
                                <div class="col-sm-6">
                                    <small class="d-block">
                                        <strong>Contraseña:</strong><br>
                                        <code>password123</code>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\laragon\www\bodega-app\resources\views/auth/login.blade.php ENDPATH**/ ?>