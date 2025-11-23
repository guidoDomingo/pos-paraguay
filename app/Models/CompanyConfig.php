<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyConfig extends Model
{
    use HasFactory;

    protected $table = 'company_config';

    protected $fillable = [
        'company_id',
        'default_fiscal_stamp_id',
        'default_iva_rate',
        'invoice_footer_text',
        'ticket_footer_text',
        'print_after_sale',
        'default_print_type',
        'printer_config',
    ];

    protected $casts = [
        'default_iva_rate' => 'decimal:2',
        'print_after_sale' => 'boolean',
        'printer_config' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function defaultFiscalStamp(): BelongsTo
    {
        return $this->belongsTo(FiscalStamp::class, 'default_fiscal_stamp_id');
    }
}