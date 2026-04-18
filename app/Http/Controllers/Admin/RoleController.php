<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Permisos agrupados por categoría para la UI
    public static function permissionGroups(): array
    {
        return [
            'Terminal POS'  => ['pos.access', 'pos.sell'],
            'Productos'     => ['products.view', 'products.create', 'products.edit', 'products.delete'],
            'Ventas'        => ['sales.view', 'sales.create'],
            'Facturas'      => ['invoices.view', 'invoices.create'],
            'Clientes'      => ['customers.view', 'customers.create', 'customers.edit'],
            'Inventario'    => ['inventory.view', 'inventory.adjust'],
            'Caja'          => ['cash_register.open', 'cash_register.close'],
            'Reportes'      => ['reports.view'],
            'Administración'=> ['admin.users', 'admin.company', 'admin.settings', 'fiscal_stamps.manage'],
        ];
    }

    public function index()
    {
        $roles = Role::withCount('users')->orderBy('display_name')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $groups = self::permissionGroups();
        return view('admin.roles.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:50|unique:roles,name|regex:/^[a-z_]+$/',
            'display_name' => 'required|string|max:100',
            'description'  => 'nullable|string|max:500',
            'permissions'  => 'nullable|array',
        ]);

        Role::create([
            'name'         => $request->name,
            'display_name' => $request->display_name,
            'description'  => $request->description,
            'permissions'  => $request->input('permissions', []),
            'is_active'    => true,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    public function show(Role $role)
    {
        return redirect()->route('admin.roles.edit', $role);
    }

    public function edit(Role $role)
    {
        $groups = self::permissionGroups();
        return view('admin.roles.edit', compact('role', 'groups'));
    }
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'display_name' => 'required|string|max:100',
            'description'  => 'nullable|string|max:500',
            'permissions'  => 'nullable|array',
        ]);

        $role->update([
            'display_name' => $request->display_name,
            'description'  => $request->description,
            'permissions'  => $request->input('permissions', []),
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol actualizado. Los cambios se aplican inmediatamente a todos los usuarios con este rol.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return back()->with('error', 'El rol Administrador no puede ser eliminado.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'No se puede eliminar un rol que tiene usuarios asignados.');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol eliminado.');
    }
}
