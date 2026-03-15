<?php

// Archivo: fix-admin-permissions.php
// Ejecutar con: php fix-admin-permissions.php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "🔧 Solucionando permisos de Gestión de Datos...\n\n";

    // 1. Crear o actualizar rol Admin
    $adminRole = App\Models\Role::updateOrCreate(
        ['name' => 'admin'],
        [
            'display_name' => 'Administrador',
            'description' => 'Acceso completo al sistema',
            'permissions' => [
                'products.view',
                'products.create', 
                'products.edit',
                'products.delete',
                'categories.view',
                'categories.create',
                'categories.edit', 
                'categories.delete',
                'sales.view',
                'sales.create',
                'invoices.view',
                'invoices.create',
                'customers.view',
                'customers.create',
                'customers.edit',
                'reports.view',
                'cash_register.open',
                'cash_register.close',
                'admin.users',
                'admin.company',
                'admin.settings', // ← PERMISO CLAVE
                'inventory.view',
                'inventory.adjust',
                'fiscal_stamps.manage',
            ],
            'is_active' => true,
        ]
    );
    
    echo "✅ Rol 'admin' creado/actualizado con permiso 'admin.settings'\n";

    // 2. Buscar usuarios sin rol admin y asignarles admin
    $usersWithoutAdminRole = App\Models\User::whereDoesntHave('role', function($q) {
        $q->where('name', 'admin');
    })->get();

    foreach($usersWithoutAdminRole as $user) {
        $user->role_id = $adminRole->id;
        $user->save();
        echo "✅ Asignado rol admin a usuario: {$user->email}\n";
    }

    // 3. Verificar usuarios con permisos admin.settings
    $adminUsers = App\Models\User::whereHas('role', function($q) {
        $q->where('name', 'admin');
    })->get();

    echo "\n📋 Usuarios con acceso a Gestión de Datos:\n";
    foreach($adminUsers as $user) {
        $hasPermission = $user->hasPermission('admin.settings') ? '✅' : '❌';
        echo "{$hasPermission} {$user->email} ({$user->name})\n";
    }

    echo "\n🎉 ¡Listo! Ahora deberías ver 'Gestión de Datos' en el menú.\n";
    echo "🔄 Recuerda limpiar cache: php artisan cache:clear\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📋 Línea: " . $e->getLine() . "\n";
    echo "📁 Archivo: " . $e->getFile() . "\n";
}