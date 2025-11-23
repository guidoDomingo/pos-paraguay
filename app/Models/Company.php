<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'trade_name',
        'ruc',
        'dv',
        'address',
        'phone',
        'email',
        'logo_path',
        'activity_description',
        'taxpayer_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function config(): HasOne
    {
        return $this->hasOne(CompanyConfig::class);
    }

    public function fiscalStamps(): HasMany
    {
        return $this->hasMany(FiscalStamp::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getFormattedRucAttribute(): string
    {
        return $this->ruc . '-' . $this->dv;
    }

    public function getActiveFiscalStamp(): ?FiscalStamp
    {
        return $this->fiscalStamps()
            ->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->first();
    }
}