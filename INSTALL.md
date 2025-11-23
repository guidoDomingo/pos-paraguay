# Sistema POS Paraguay - Instalación en Laragon

## Requisitos Previos
- Laragon instalado y funcionando
- PHP 8.1 o superior
- Composer
- Node.js y NPM

## Pasos de Instalación

### 1. Clonar o crear el proyecto
```bash
# Navegar al directorio de Laragon
cd C:\laragon\www

# Si es proyecto nuevo, crear desde la carpeta bodega-app existente
# El proyecto ya está configurado en bodega-app
```

### 2. Instalar dependencias PHP
```bash
cd bodega-app
composer install
```

### 3. Configurar variables de entorno
```bash
# Copiar el archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Configurar base de datos en .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bodega_pos
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Crear base de datos
- Abrir HeidiSQL desde Laragon
- Crear nueva base de datos: `bodega_pos`

### 6. Ejecutar migraciones
```bash
php artisan migrate
```

### 7. Instalar Livewire
```bash
composer require livewire/livewire
```

### 8. Instalar dependencias frontend
```bash
npm install
npm run dev
```

### 9. Configurar sistema inicial
```bash
# Configuración inicial del sistema
php artisan pos:setup

# Seguir las instrucciones en pantalla:
# - Nombre de empresa
# - RUC y dígito verificador  
# - Dirección
# - Datos del administrador
```

### 10. Crear timbrado fiscal
```bash
# Crear timbrado para facturación
php artisan pos:create-fiscal-stamp

# Proporcionar:
# - Número de timbrado (8 dígitos)
# - Establecimiento (001)
# - Punto de expedición (002)
# - Fechas de vigencia
```

### 11. Configurar servidor local
```bash
# Opción 1: Usar servidor de desarrollo
php artisan serve

# Opción 2: Usar Laragon (recomendado)
# Acceder via: http://bodega-app.test
```

## Configuración Adicional

### Configurar Livewire en AppServiceProvider
Agregar en `app/Providers/AppServiceProvider.php`:

```php
use Livewire\Livewire;

public function boot()
{
    Livewire::component('pos-terminal', \App\Http\Livewire\PosTerminal::class);
}
```

### Configurar middleware de autenticación
En `app/Http/Middleware/Authenticate.php`:

```php
protected function redirectTo($request)
{
    if (! $request->expectsJson()) {
        return route('login');
    }
}
```

## Comandos Útiles

### Gestión del Sistema
```bash
# Ver estadísticas del timbrado fiscal
php artisan pos:fiscal-stats

# Crear productos de ejemplo
php artisan pos:create-sample-products

# Crear usuarios adicionales
php artisan pos:create-user
```

### Desarrollo
```bash
# Compilar assets en modo desarrollo
npm run dev

# Compilar assets para producción
npm run build

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Estructura del Proyecto

```
bodega-app/
├── app/
│   ├── Console/Commands/          # Comandos Artisan
│   ├── Http/
│   │   ├── Controllers/          # Controladores
│   │   └── Livewire/            # Componentes Livewire
│   ├── Models/                   # Modelos Eloquent
│   └── Services/                # Servicios de negocio
├── database/
│   ├── migrations/              # Migraciones
│   └── seeders/                 # Seeders
├── resources/
│   └── views/
│       ├── invoices/            # Plantillas de facturas
│       ├── tickets/             # Plantillas de tickets
│       └── livewire/           # Vistas Livewire
└── routes/
    └── web.php                  # Rutas web
```

## Acceso al Sistema

### Usuario Administrador
- URL: http://bodega-app.test/login
- Email: (configurado durante setup)
- Contraseña: (configurada durante setup)

### Funcionalidades Principales
1. **Terminal POS**: http://bodega-app.test/pos
2. **Dashboard**: http://bodega-app.test/dashboard
3. **Gestión de Productos**: (por implementar en siguientes fases)
4. **Reportes**: (por implementar en siguientes fases)

## Solución de Problemas

### Error de permisos
```bash
# En Windows con Laragon, generalmente no hay problemas de permisos
# Si hay errores, verificar que Laragon esté ejecutándose como administrador
```

### Error de base de datos
```bash
# Verificar que MySQL esté corriendo en Laragon
# Verificar configuración de .env
# Recrear base de datos si es necesario
```

### Error de Livewire
```bash
# Limpiar cache de vistas
php artisan view:clear

# Republicar assets de Livewire
php artisan livewire:publish
```

### Error en compilación de assets
```bash
# Reinstalar node_modules
rm -rf node_modules
npm install
npm run dev
```

## Notas Importantes

1. **Timbrado Fiscal**: Es obligatorio configurar un timbrado válido antes de emitir facturas
2. **Backup**: Realizar copias de seguridad periódicas de la base de datos
3. **Actualizaciones**: Seguir las prácticas de Laravel para actualizaciones
4. **Impresoras**: Configurar impresoras térmicas según fabricante
5. **DNIT**: Las facturas cumplen con los requisitos de la DNIT Paraguay