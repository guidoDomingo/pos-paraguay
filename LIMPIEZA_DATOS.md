# 🧹 Sistema de Limpieza de Datos - Guía de Uso

## Descripción
Esta funcionalidad permite limpiar datos transaccionales del sistema, dejándolo en estado inicial mientras preserva datos maestros importantes como configuraciones, usuarios, productos y timbres fiscales.

## ⚠️ IMPORTANTE
**Los timbres fiscales NUNCA se eliminan** por ser un requerimiento legal en Paraguay.

## Formas de Uso

### 1. 🖥️ Interfaz Web
- Navega a **"Gestión de Datos"** en el menú principal
- Solo disponible para usuarios con permisos de `admin.settings`
- Interfaz intuitiva con opciones configurables
- Confirmación requerida antes de ejecutar

### 2. 🛠️ Comando de Terminal

#### Uso Básico
```bash
php artisan system:clean-data
```

#### Opciones Disponibles
```bash
# Limpiar empresa específica
php artisan system:clean-data --company=1

# Preservar productos y su stock
php artisan system:clean-data --preserve-products

# Preservar clientes
php artisan system:clean-data --preserve-customers

# Resetear stock a valor específico
php artisan system:clean-data --reset-stock=100

# Crear datos de prueba después de limpiar
php artisan system:clean-data --seed-demo

# Ejecutar sin confirmación (para scripts automatizados)
php artisan system:clean-data --force

# Combinando opciones
php artisan system:clean-data --preserve-products --reset-stock=50 --seed-demo
```

## 📊 Tipos de Limpieza

### Solo Transacciones ✅ (Recomendado)
- ❌ Elimina: Ventas, facturas, movimientos de stock, pagos, sesiones de caja
- ✅ Preserva: Productos, clientes, configuraciones, usuarios, timbres

### Transacciones + Historial de Clientes
- ❌ Elimina: Todo lo anterior + historial de compras de clientes
- ✅ Preserva: Clientes (sin historial), productos, configuraciones

### Limpieza Personalizada
- Permite configurar exactamente qué preservar
- Opciones granulares para productos, clientes, stock

## 💾 Datos que SE MANTIENEN SIEMPRE

| Tabla | Descripción | Motivo |
|-------|-------------|---------|
| **companies** | Información de empresas | Datos maestros |
| **users** | Usuarios del sistema | Acceso al sistema |
| **roles** | Roles y permisos | Sistema de autorización |
| **fiscal_stamps** | Timbres fiscales | **REQUERIMIENTO LEGAL** |
| **warehouses** | Almacenes | Estructura operativa |
| **categories** | Categorías | Organización de productos |
| **products** | Productos | Catálogo (configurable) |
| **customers** | Clientes | Relaciones comerciales (configurable) |
| **suppliers** | Proveedores | Relaciones comerciales |
| **company_config** | Configuraciones | Personalización del sistema |

## 🗑️ Datos que SE ELIMINAN

| Tabla | Descripción |
|-------|-------------|
| **sales** | Todas las ventas |
| **sale_items** | Items de ventas |
| **invoices** | Facturas electrónicas |
| **invoice_items** | Items de facturas |
| **stock_movements** | Movimientos de inventario |
| **inventory_movements** | Historial de inventario |
| **cash_registers** | Sesiones de caja |
| **payments** | Pagos de créditos |
| **invoice_settings** | Configuraciones de impresión |

## 🌱 Datos de Prueba

### Crear Datos de Prueba
```bash
# Solo crear datos de prueba (sin limpiar)
php artisan db:seed --class=DemoDataSeeder

# Limpiar Y crear datos de prueba
php artisan system:clean-data --seed-demo
```

### Datos de Prueba Incluidos
- 3 clientes de ejemplo (individual, VIP, empresa)
- 10 ventas históricas (70% contado, 30% crédito)
- Productos con stock actualizado
- Transacciones distribuidas en los últimos 30 días

## 🔒 Seguridad y Permisos

### Permisos Requeridos
- **Interfaz Web**: Permiso `admin.settings`
- **Comando Terminal**: Acceso al servidor

### Protecciones Implementadas
- Confirmación obligatoria antes de ejecutar
- Validación de permisos de usuario
- Transacciones de base de datos (rollback en caso de error)
- Logging de todas las actividades
- Preservación automática de datos críticos

## 🚨 Consideraciones de Producción

### ⚠️ ANTES DE USAR EN PRODUCCIÓN
1. **SIEMPRE hacer backup** de la base de datos
2. Verificar que realmente necesitas limpiar TODO
3. Considerar limpiar solo tablas específicas
4. Informar al equipo sobre la operación
5. Programar durante horarios de menor actividad

### 💡 Casos de Uso Recomendados
- ✅ Limpiar datos de testing/desarrollo
- ✅ Reiniciar sistema para nueva temporada
- ✅ Migración de datos después de importar master data
- ❌ Limpieza regular (mejor usar archivos periódicos)
- ❌ Eliminar solo algunas ventas específicas

## 🔧 Personalización

### Modificar Qué Tablas se Limpian
Editar el método `cleanTransactionalData()` en:
- `app/Http/Controllers/DataManagementController.php`
- `app/Console/Commands/CleanSystemDataCommand.php`

### Agregar Nuevos Tipos de Limpieza
1. Agregar opción en la vista `index.blade.php`
2. Agregar lógica en el controlador
3. Actualizar el comando de terminal

## 📞 Soporte

Si encuentras problemas:
1. Revisar los logs de Laravel en `storage/logs/`
2. Verificar permisos de usuario
3. Asegurar que las migraciones estén actualizadas
4. Contactar al administrador del sistema

---
**Última actualización**: Marzo 2026  
**Versión**: 1.0.0