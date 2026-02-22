<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FacturaSend Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración para integración con FacturaSend.com.py
    | Servicio de facturación electrónica para Paraguay
    |
    */

    'api_key' => env('FACTURASEND_API_KEY'),
    
    'tenant_id' => env('FACTURASEND_TENANT_ID'),
    
    'environment' => env('FACTURASEND_ENVIRONMENT', 'test'), // test o production
    
    'enabled' => env('FACTURASEND_ENABLED', false),
    
    /*
    |--------------------------------------------------------------------------
    | URLs de FacturaSend
    |--------------------------------------------------------------------------
    */
    
    'urls' => [
        // FacturaSend usa la misma URL para test y production
        // La diferencia está en la configuración del ambiente en el panel
        'api' => 'https://api.facturasend.com.py',
        'console' => 'https://console.facturasend.com.py',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configuraciones por defecto
    |--------------------------------------------------------------------------
    */
    
    'defaults' => [
        'tipo_documento' => 1, // 1 = Factura Electrónica
        'tipo_emision' => 1,   // 1 = Normal, 2 = Contingencia  
        'tipo_transaccion' => 1, // 1 = Venta de mercadería
        'tipo_impuesto' => 1,    // 1 = IVA
        'moneda' => 'PYG',
        'unidad_medida' => 77,   // 77 = Unidad (estándar)
        'departamento' => 11,    // Central
        'distrito' => 1424,      // Asunción
        'ciudad' => 3344,        // Asunción
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de timeouts y límites
    |--------------------------------------------------------------------------
    */
    
    'timeout' => 30, // Timeout en segundos para llamadas API
    'max_items_per_batch' => 50, // Máximo de documentos por lote
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de logs
    |--------------------------------------------------------------------------
    */
    
    'log_requests' => env('FACTURASEND_LOG_REQUESTS', false),
    'log_responses' => env('FACTURASEND_LOG_RESPONSES', false),
];