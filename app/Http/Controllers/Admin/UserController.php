<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('company_id', Auth::user()->company_id)
            ->with('role')
            ->orderBy('name');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%")
                ->orWhere('employee_code', 'like', "%$s%"));
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = Role::where('is_active', true)->orderBy('display_name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->orderBy('display_name')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => ['required', 'confirmed', Password::min(8)],
            'role_id'       => 'required|exists:roles,id',
            'employee_code' => 'nullable|string|max:10|unique:users,employee_code',
            'phone'         => 'nullable|string|max:20',
            'is_active'     => 'boolean',
        ]);

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'company_id'    => Auth::user()->company_id,
            'role_id'       => $request->role_id,
            'employee_code' => $request->employee_code,
            'phone'         => $request->phone,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function show(User $user)
    {
        $this->authorizeCompany($user);
        $user->load('role');
        $salesCount = $user->sales()->count();
        $lastSale   = $user->sales()->latest('sale_date')->first();
        return view('admin.users.show', compact('user', 'salesCount', 'lastSale'));
    }

    public function edit(User $user)
    {
        $this->authorizeCompany($user);
        $roles = Role::where('is_active', true)->orderBy('display_name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeCompany($user);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'password'      => ['nullable', 'confirmed', Password::min(8)],
            'role_id'       => 'required|exists:roles,id',
            'employee_code' => 'nullable|string|max:10|unique:users,employee_code,' . $user->id,
            'phone'         => 'nullable|string|max:20',
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'role_id'       => $request->role_id,
            'employee_code' => $request->employee_code,
            'phone'         => $request->phone,
            'is_active'     => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // No permitir que el admin se desactive a sí mismo
        if ($user->id === Auth::id()) {
            $data['is_active'] = true;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function toggleActive(User $user)
    {
        $this->authorizeCompany($user);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes desactivarte a ti mismo.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $estado = $user->is_active ? 'activado' : 'desactivado';

        return back()->with('success', "Usuario {$estado} exitosamente.");
    }

    public function destroy(User $user)
    {
        $this->authorizeCompany($user);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        if ($user->sales()->exists()) {
            return back()->with('error', 'No se puede eliminar un usuario que tiene ventas registradas.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado.');
    }

    private function authorizeCompany(User $user): void
    {
        if ($user->company_id !== Auth::user()->company_id) {
            abort(403);
        }
    }
}
