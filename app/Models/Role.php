<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
        'is_active',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public static function getDefaultPermissions(): array
    {
        return [
            'pos.access',
            'pos.sell',
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
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
            'admin.settings',
        ];
    }
}