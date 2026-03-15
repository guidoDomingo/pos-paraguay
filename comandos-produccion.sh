# Comandos para Producción - Gestión de Datos

## 1. VERIFICAR ESTADO ACTUAL
# Conectar por SSH a producciotherwise verificar:

# Verificar si existen roles
php artisan tinker
App\Models\Role::all();
exit

# Verificar rol del usuario actual  
php artisan tinker
$user = App\Models\User::where('email', 'tu-email@ejemplo.com')->first();
$user->role;
$user->role->permissions ?? [];
exit

## 2. EJECUTAR SEEDERS (SI NO EXISTEN ROLES)
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UserSeeder

## 3. ASIGNAR ROL ADMIN A USUARIO (SI EXISTE PERO SIN PERMISOS)
php artisan tinker
$adminRole = App\Models\Role::where('name', 'admin')->first();
$user = App\Models\User::where('email', 'tu-email@ejemplo.com')->first();
$user->role_id = $adminRole->id;
$user->save();
exit

## 4. VERIFICAR PERMISOS DESPUÉS
php artisan tinker
$user = App\Models\User::where('email', 'tu-email@ejemplo.com')->first();
$user->hasPermission('admin.settings');
exit

## 5. LIMPIAR CACHE
php artisan config:cache
php artisan view:cache
php artisan route:cache