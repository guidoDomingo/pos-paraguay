<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_activity',
        'company_ruc',
        'company_address',
        'company_phone',
        'company_email',
        'company_logo',
        'invoice_prefix',
        'invoice_suffix',
        'invoice_counter',
        'invoice_auto_increment',
        'ticket_prefix',
        'ticket_suffix',
        'ticket_counter',
        'ticket_auto_increment',
        'paper_size',
        'orientation',
        'footer_text',
        'terms_conditions',
        'default_iva_rate',
        'default_printer',
        'ticket_printer',
        'invoice_printer',
        'auto_print_tickets',
        'auto_print_invoices',
        'printer_type',
        'printer_left_margin',
        'printer_width',
    ];

    protected $casts = [
        'invoice_auto_increment' => 'boolean',
        'ticket_auto_increment' => 'boolean',
        'auto_print_tickets' => 'boolean',
        'auto_print_invoices' => 'boolean',
        'default_iva_rate' => 'decimal:2',
    ];

    /**
     * Get the singleton instance of invoice settings
     */
    public static function getSettings()
    {
        return static::first() ?: static::create([]);
    }

    /**
     * Generate next invoice number
     */
    public function getNextInvoiceNumber()
    {
        if ($this->invoice_auto_increment) {
            $number = $this->invoice_prefix . str_pad($this->invoice_counter, 6, '0', STR_PAD_LEFT) . $this->invoice_suffix;
            $this->increment('invoice_counter');
            return $number;
        }
        
        return $this->invoice_prefix . str_pad($this->invoice_counter, 6, '0', STR_PAD_LEFT) . $this->invoice_suffix;
    }

    /**
     * Generate next ticket number
     */
    public function getNextTicketNumber()
    {
        if ($this->ticket_auto_increment) {
            $number = $this->ticket_prefix . str_pad($this->ticket_counter, 6, '0', STR_PAD_LEFT) . $this->ticket_suffix;
            $this->increment('ticket_counter');
            return $number;
        }
        
        return $this->ticket_prefix . str_pad($this->ticket_counter, 6, '0', STR_PAD_LEFT) . $this->ticket_suffix;
    }
}
