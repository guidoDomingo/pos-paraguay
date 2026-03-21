<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            <i class="bi bi-gear me-2"></i>Configuración de Facturas y Tickets
        </h2>
    </x-slot>

    <div class="py-4" x-data="{
        activeTab: 'factura',
        s: {
            company_name:     @js($settings->company_name ?? ''),
            company_activity: @js($settings->company_activity ?? ''),
            company_ruc:      @js($settings->company_ruc ?? ''),
            company_phone:    @js($settings->company_phone ?? ''),
            company_address:  @js($settings->company_address ?? ''),
            company_email:    @js($settings->company_email ?? ''),
                ticket_prefix:    @js($settings->ticket_prefix ?? 'T'),
            ticket_counter:   {{ $settings->ticket_counter ?? 1 }},
            ticket_suffix:    @js($settings->ticket_suffix ?? ''),
            footer_text:      @js($settings->footer_text ?? ''),
            paper_size:       @js($settings->paper_size ?? 'A4'),
        },
        ticketNumberPreview() {
            const num = String(this.s.ticket_counter).padStart(8, '0');
            return (this.s.ticket_prefix || '') + num + (this.s.ticket_suffix || '');
        }
    }">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i><strong>Errores al guardar:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">

                {{-- ══════════════ FORMULARIO (izquierda) ══════════════ --}}
                <div class="col-lg-5">
                    <form method="POST" action="{{ route('settings.invoice.update') }}" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        {{-- Empresa --}}
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header fw-bold">
                                <i class="bi bi-building me-2"></i>Datos de la Empresa
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label form-label-sm">Nombre / Razón Social *</label>
                                    <input type="text" name="company_name" class="form-control form-control-sm"
                                        value="{{ old('company_name', $settings->company_name) }}"
                                        x-model="s.company_name" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label form-label-sm">Actividad Económica</label>
                                    <input type="text" name="company_activity" class="form-control form-control-sm"
                                        value="{{ old('company_activity', $settings->company_activity ?? '') }}"
                                        x-model="s.company_activity">
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label form-label-sm">RUC</label>
                                        <input type="text" name="company_ruc" class="form-control form-control-sm"
                                            value="{{ old('company_ruc', $settings->company_ruc) }}"
                                            x-model="s.company_ruc">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label form-label-sm">Teléfono</label>
                                        <input type="text" name="company_phone" class="form-control form-control-sm"
                                            value="{{ old('company_phone', $settings->company_phone) }}"
                                            x-model="s.company_phone">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label form-label-sm">Dirección</label>
                                    <input type="text" name="company_address" class="form-control form-control-sm"
                                        value="{{ old('company_address', $settings->company_address) }}"
                                        x-model="s.company_address">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label form-label-sm">Email</label>
                                    <input type="email" name="company_email" class="form-control form-control-sm"
                                        value="{{ old('company_email', $settings->company_email) }}"
                                        x-model="s.company_email">
                                </div>
                            </div>
                        </div>

                        {{-- Numeración de tickets --}}
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header fw-bold">
                                <i class="bi bi-receipt me-2"></i>Numeración de Tickets
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-2">
                                    <div class="col-4">
                                        <label class="form-label form-label-sm">Prefijo</label>
                                        <input type="text" name="ticket_prefix" class="form-control form-control-sm"
                                            value="{{ old('ticket_prefix', $settings->ticket_prefix ?? 'T') }}"
                                            x-model="s.ticket_prefix" maxlength="5">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label form-label-sm">Contador actual</label>
                                        <input type="number" name="ticket_counter" class="form-control form-control-sm"
                                            value="{{ old('ticket_counter', $settings->ticket_counter ?? 1) }}"
                                            x-model="s.ticket_counter" min="1">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label form-label-sm">Sufijo</label>
                                        <input type="text" name="ticket_suffix" class="form-control form-control-sm"
                                            value="{{ old('ticket_suffix', $settings->ticket_suffix ?? '') }}"
                                            x-model="s.ticket_suffix" maxlength="5">
                                    </div>
                                </div>
                                <div class="form-check form-switch mb-1">
                                    <input class="form-check-input" type="checkbox" name="ticket_auto_increment"
                                        id="ticket_auto_increment"
                                        {{ old('ticket_auto_increment', $settings->ticket_auto_increment) ? 'checked' : '' }}>
                                    <label class="form-check-label form-label-sm" for="ticket_auto_increment">
                                        Auto-incrementar al emitir
                                    </label>
                                </div>
                                <div class="alert alert-light border py-1 px-2 mt-2 mb-0" style="font-size:12px;">
                                    Vista previa: <strong x-text="ticketNumberPreview()"></strong>
                                </div>
                            </div>
                        </div>

                        {{-- Pie de página --}}
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header fw-bold">
                                <i class="bi bi-text-left me-2"></i>Pie de Página
                            </div>
                            <div class="card-body">
                                <div class="mb-0">
                                    <label class="form-label form-label-sm">Texto del pie (factura y ticket)</label>
                                    <textarea name="footer_text" class="form-control form-control-sm" rows="2"
                                        x-model="s.footer_text">{{ old('footer_text', $settings->footer_text) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Impresión --}}
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header fw-bold">
                                <i class="bi bi-printer me-2"></i>Configuración de Impresión
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label form-label-sm">Tamaño papel (factura)</label>
                                        <select name="paper_size" class="form-select form-select-sm" x-model="s.paper_size">
                                            <option value="A4">A4 (doble copia)</option>
                                            <option value="Letter">Carta - Letter (doble copia)</option>
                                            <option value="Ticket">Ticket 80mm (ticketeadora)</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label form-label-sm">Orientación</label>
                                        <select name="orientation" class="form-select form-select-sm">
                                            <option value="portrait"  {{ ($settings->orientation ?? 'portrait') === 'portrait'  ? 'selected' : '' }}>Vertical</option>
                                            <option value="landscape" {{ ($settings->orientation ?? '') === 'landscape' ? 'selected' : '' }}>Horizontal</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label form-label-sm">IVA por defecto (%)</label>
                                        <input type="number" name="default_iva_rate" class="form-control form-control-sm"
                                            value="{{ old('default_iva_rate', $settings->default_iva_rate ?? 10) }}"
                                            min="0" max="100" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-4">
                            <i class="bi bi-check-circle me-1"></i>Guardar Configuración
                        </button>
                    </form>
                </div>

                {{-- ══════════════ PREVIEW (derecha) ══════════════ --}}
                <div class="col-lg-7">
                    <div class="sticky-top" style="top: 80px;">

                        <ul class="nav nav-tabs mb-0">
                            <li class="nav-item">
                                <button class="nav-link" :class="activeTab==='factura' ? 'active' : ''"
                                    @click="activeTab='factura'" type="button">
                                    <i class="bi bi-file-text me-1"></i>Factura
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" :class="activeTab==='ticket' ? 'active' : ''"
                                    @click="activeTab='ticket'" type="button">
                                    <i class="bi bi-receipt me-1"></i>Ticket
                                </button>
                            </li>
                        </ul>

                        <div class="border border-top-0 rounded-bottom bg-light p-3"
                             style="max-height:80vh; overflow-y:auto;">

                            {{-- ── PREVIEW FACTURA ── --}}
                            <div x-show="activeTab==='factura'">

                                {{-- Preview A4 / Letter --}}
                                <div x-show="s.paper_size !== 'Ticket'" style="background:#fff;border:2px solid #000;padding:8px;font-family:Arial,sans-serif;font-size:9px;color:#000;max-width:660px;margin:0 auto;">
                                    <table style="width:100%;border-collapse:collapse;border-bottom:2px solid #000;margin-bottom:6px;">
                                        <tr>
                                            <td style="width:60%;border-right:2px solid #000;padding:4px 6px;vertical-align:top;">
                                                <div style="font-size:13px;font-weight:bold;text-decoration:underline;margin-bottom:3px;" x-text="s.company_name || 'Nombre de la Empresa'"></div>
                                                <div style="font-size:8px;" x-text="s.company_activity"></div>
                                                <div style="font-size:8px;" x-text="s.company_phone ? 'Cel.: '+s.company_phone : ''"></div>
                                                <div style="font-size:8px;" x-text="s.company_address"></div>
                                            </td>
                                            <td style="width:40%;padding:4px 6px;vertical-align:top;text-align:center;">
                                                @if($fiscalStamp)
                                                    <div style="font-size:8px;font-weight:bold;">TIMBRADO Nº</div>
                                                    <div style="font-size:9px;font-weight:bold;">{{ $fiscalStamp->stamp_number }}</div>
                                                    <div style="font-size:8px;" x-text="'RUC: '+(s.company_ruc||'0000000')"></div>
                                                    <div style="font-size:18px;font-weight:bold;letter-spacing:2px;">FACTURA</div>
                                                    <div style="font-size:7.5px;">Fecha Inicio Vigencia: {{ $fiscalStamp->valid_from->format('d/m/Y') }}</div>
                                                    <div style="font-size:7.5px;">Válido Hasta: {{ $fiscalStamp->valid_until->format('d/m/Y') }}</div>
                                                    <div style="border:2px solid #000;display:inline-block;padding:2px 8px;font-size:10px;font-weight:bold;margin-top:4px;">
                                                        {{ $fiscalStamp->establishment }}-{{ $fiscalStamp->point_of_sale }}-0000001
                                                    </div>
                                                @else
                                                    <div style="font-size:8px;color:#999;">Sin timbrado activo</div>
                                                    <div style="font-size:8px;" x-text="'RUC: '+(s.company_ruc||'0000000')"></div>
                                                    <div style="font-size:18px;font-weight:bold;letter-spacing:2px;">FACTURA</div>
                                                    <div style="border:2px solid #000;display:inline-block;padding:2px 8px;font-size:10px;font-weight:bold;margin-top:4px;">001-001-0000001</div>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width:100%;border-collapse:collapse;border:1px solid #000;margin-bottom:6px;">
                                        <tr>
                                            <td style="width:55%;border:1px solid #000;padding:3px 5px;font-size:8px;">Fecha de Emisión: <strong>{{ now()->day }} de {{ now()->translatedFormat('F') }} de {{ now()->year }}</strong></td>
                                            <td style="border:1px solid #000;padding:3px 5px;font-size:8px;">Cond. de Venta: <span style="display:inline-block;width:9px;height:9px;border:1px solid #000;text-align:center;line-height:8px;font-size:7px;font-weight:bold;">X</span> Contado &nbsp;<span style="display:inline-block;width:9px;height:9px;border:1px solid #000;"></span> Crédito</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #000;padding:3px 5px;font-size:8px;">Nombre o Razón Social: <strong>Juan Pérez</strong></td>
                                            <td style="border:1px solid #000;padding:3px 5px;font-size:8px;">Nota de Remisión Nº:</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #000;padding:3px 5px;font-size:8px;">R.U.C.: <strong>1234567-8</strong></td>
                                            <td style="border:1px solid #000;padding:3px 5px;font-size:8px;">Teléfono: <strong>0981 000 000</strong></td>
                                        </tr>
                                        <tr><td colspan="2" style="border:1px solid #000;padding:3px 5px;font-size:8px;">Dirección: <strong>Av. España 123, Asunción</strong></td></tr>
                                    </table>
                                    <table style="width:100%;border-collapse:collapse;margin-bottom:0;font-size:8px;">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="border:1px solid #000;padding:2px 3px;text-align:center;width:7%;">Cant.</th>
                                                <th rowspan="2" style="border:1px solid #000;padding:2px 3px;width:44%;">Descripción</th>
                                                <th rowspan="2" style="border:1px solid #000;padding:2px 3px;text-align:right;width:14%;">P/U</th>
                                                <th colspan="3" style="border:1px solid #000;padding:2px 3px;text-align:center;">VALOR DE VENTA</th>
                                            </tr>
                                            <tr>
                                                <th style="border:1px solid #000;padding:2px 3px;text-align:center;width:12%;">EXENTAS</th>
                                                <th style="border:1px solid #000;padding:2px 3px;text-align:center;width:11%;">5 %</th>
                                                <th style="border:1px solid #000;padding:2px 3px;text-align:center;width:12%;">10 %</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td style="border:1px solid #000;padding:2px 3px;text-align:center;">2</td><td style="border:1px solid #000;padding:2px 3px;">Remera blanca talle M</td><td style="border:1px solid #000;padding:2px 3px;text-align:right;">25.000</td><td style="border:1px solid #000;padding:2px 3px;"></td><td style="border:1px solid #000;padding:2px 3px;"></td><td style="border:1px solid #000;padding:2px 3px;text-align:right;">50.000</td></tr>
                                            <tr><td style="border:1px solid #000;padding:2px 3px;text-align:center;">1</td><td style="border:1px solid #000;padding:2px 3px;">Pantalón negro talle L</td><td style="border:1px solid #000;padding:2px 3px;text-align:right;">80.000</td><td style="border:1px solid #000;padding:2px 3px;"></td><td style="border:1px solid #000;padding:2px 3px;"></td><td style="border:1px solid #000;padding:2px 3px;text-align:right;">80.000</td></tr>
                                            @for($i=0;$i<6;$i++)<tr><td style="border:1px solid #000;padding:2px 3px;height:11px;"></td><td style="border:1px solid #000;padding:2px 3px;"></td><td style="border:1px solid #000;padding:2px 3px;"></td><td style="border:1px solid #000;padding:2px 3px;"></td><td style="border:1px solid #000;padding:2px 3px;"></td><td style="border:1px solid #000;padding:2px 3px;"></td></tr>@endfor
                                        </tbody>
                                    </table>
                                    <table style="width:100%;border-collapse:collapse;">
                                        <tr><td style="border:1px solid #000;padding:3px 5px;font-weight:bold;width:55%;font-size:8.5px;">VALOR PARCIAL</td><td style="border:1px solid #000;padding:3px 5px;text-align:right;font-size:8.5px;">130.000</td></tr>
                                        <tr><td style="border:1px solid #000;padding:3px 5px;font-weight:bold;font-size:10px;">TOTAL A PAGAR Gs.</td><td style="border:1px solid #000;padding:3px 5px;text-align:right;font-weight:bold;font-size:10px;">130.000</td></tr>
                                        <tr><td colspan="2" style="border:1px solid #000;padding:3px 5px;font-size:8px;">SON: <strong>CIENTO TREINTA MIL GUARANÍES</strong></td></tr>
                                    </table>
                                    <table style="width:100%;border-collapse:collapse;">
                                        <tr><td style="border:1px solid #000;padding:3px 5px;font-size:8.5px;width:55%;">TOTAL IVA: &nbsp;<strong>11.818</strong></td><td style="border:1px solid #000;padding:3px 5px;font-size:7.5px;text-align:right;font-weight:bold;">Original: Comprador &nbsp;|&nbsp; Primera Copia: Arch. Tributario</td></tr>
                                        <tr><td colspan="2" style="border:1px solid #000;padding:3px 5px;font-size:8.5px;">Liquidación del IVA:&nbsp; (5 %) <strong>0</strong> &nbsp;&nbsp;&nbsp; (10 %) <strong>11.818</strong></td></tr>
                                    </table>
                                    <div x-show="s.footer_text" style="font-size:7.5px;color:#555;margin-top:4px;text-align:center;" x-text="s.footer_text"></div>
                                </div>

                                {{-- Preview TICKET 80mm --}}
                                <div x-show="s.paper_size === 'Ticket'" style="background:#fff;border:1px solid #ccc;padding:10px;font-family:'Courier New',monospace;font-size:10px;color:#000;max-width:280px;margin:0 auto;">
                                    <div style="text-align:center;border-bottom:2px dashed #000;padding-bottom:8px;margin-bottom:8px;">
                                        <div style="font-size:13px;font-weight:bold;text-transform:uppercase;" x-text="s.company_name || 'NOMBRE EMPRESA'"></div>
                                        <div style="font-size:8px;" x-show="s.company_activity" x-text="s.company_activity"></div>
                                        <div style="font-size:8px;" x-show="s.company_phone" x-text="'Tel: '+s.company_phone"></div>
                                        <div style="font-size:8px;" x-show="s.company_address" x-text="s.company_address"></div>
                                    </div>
                                    <div style="text-align:center;margin-bottom:8px;">
                                        @if($fiscalStamp)
                                            <div style="font-size:8px;font-weight:bold;">TIMBRADO Nº {{ $fiscalStamp->stamp_number }}</div>
                                        @endif
                                        <div style="font-size:8px;" x-text="'RUC: '+(s.company_ruc||'0000000')"></div>
                                        <div style="font-size:16px;font-weight:bold;letter-spacing:2px;">FACTURA</div>
                                        <div style="border:2px solid #000;display:inline-block;padding:2px 6px;font-size:11px;font-weight:bold;margin-top:3px;">001-001-0000001</div>
                                    </div>
                                    <div style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:4px 0;margin-bottom:6px;font-size:8.5px;">
                                        <div>Fecha: <strong>{{ now()->day }} de {{ now()->translatedFormat('F') }} de {{ now()->year }}</strong></div>
                                        <div>Cond.: <strong>CONTADO</strong></div>
                                        <div>Cliente: <strong>Juan Pérez</strong></div>
                                        <div>RUC: <strong>1234567-8</strong></div>
                                    </div>
                                    <div style="border-bottom:1px dashed #000;padding-bottom:6px;margin-bottom:6px;">
                                        <div style="margin-bottom:4px;"><div style="font-weight:bold;font-size:9px;">Remera blanca talle M</div><div style="display:table;width:100%;font-size:8.5px;"><span style="display:table-cell;">2 x Gs. 25.000 (10%)</span><span style="display:table-cell;text-align:right;font-weight:bold;">Gs. 50.000</span></div></div>
                                        <div style="border-top:1px dotted #ccc;padding-top:4px;"><div style="font-weight:bold;font-size:9px;">Pantalón negro talle L</div><div style="display:table;width:100%;font-size:8.5px;"><span style="display:table-cell;">1 x Gs. 80.000 (10%)</span><span style="display:table-cell;text-align:right;font-weight:bold;">Gs. 80.000</span></div></div>
                                    </div>
                                    <div style="border-bottom:1px dashed #000;padding-bottom:6px;margin-bottom:6px;font-size:9px;">
                                        <div style="display:table;width:100%;"><span style="display:table-cell;">Valor Parcial:</span><span style="display:table-cell;text-align:right;font-weight:bold;">Gs. 130.000</span></div>
                                        <div style="display:table;width:100%;border-top:2px solid #000;margin-top:4px;padding-top:4px;font-size:11px;font-weight:bold;"><span style="display:table-cell;">TOTAL:</span><span style="display:table-cell;text-align:right;">Gs. 130.000</span></div>
                                    </div>
                                    <div style="font-size:8px;font-style:italic;margin-bottom:6px;">SON: CIENTO TREINTA MIL GUARANÍES</div>
                                    <div style="border-top:1px dashed #000;padding-top:4px;font-size:8px;">
                                        <div>TOTAL IVA: <strong>Gs. 11.818</strong></div>
                                        <div>Liq. IVA: (5%) <strong>0</strong> &nbsp; (10%) <strong>11.818</strong></div>
                                    </div>
                                    <div style="text-align:center;font-size:8px;margin-top:6px;border-top:1px dashed #000;padding-top:6px;">
                                        <div style="font-weight:bold;">Original: Comprador | Copia: Arch. Tributario</div>
                                        <div x-show="s.footer_text" x-text="s.footer_text" style="margin-top:3px;font-style:italic;"></div>
                                    </div>
                                </div>

                            </div>

                            {{-- ── PREVIEW TICKET ── --}}
                            <div x-show="activeTab==='ticket'" style="display:none;">
                                <div style="background:#fff;border:1px solid #ccc;padding:12px;font-family:'Courier New',monospace;font-size:11px;color:#000;max-width:280px;margin:0 auto;">

                                    <div style="text-align:center;border-bottom:2px dashed #333;padding-bottom:10px;margin-bottom:10px;">
                                        <div style="font-size:13px;font-weight:bold;text-transform:uppercase;" x-text="s.company_name || 'NOMBRE EMPRESA'"></div>
                                        <div style="font-size:9px;" x-show="s.company_ruc" x-text="'RUC: '+s.company_ruc"></div>
                                        <div style="font-size:9px;" x-show="s.company_address" x-text="s.company_address"></div>
                                        <div style="font-size:9px;" x-show="s.company_phone" x-text="'Tel: '+s.company_phone"></div>
                                    </div>

                                    <div style="text-align:center;background:#f9f9f9;padding:8px;margin-bottom:10px;border-bottom:1px dashed #333;">
                                        <div style="font-size:12px;font-weight:bold;">TICKET DE VENTA</div>
                                        <div style="font-size:9px;margin-top:4px;">
                                            <div>Nro: <strong x-text="ticketNumberPreview()"></strong></div>
                                            <div>Fecha: <strong>{{ now()->format('d/m/Y H:i') }}</strong></div>
                                            <div>Vendedor: <strong>Usuario</strong></div>
                                        </div>
                                    </div>

                                    <div style="margin-bottom:10px;border-bottom:1px dashed #333;padding-bottom:8px;">
                                        <div style="margin-bottom:6px;border-bottom:1px dotted #ccc;padding-bottom:4px;">
                                            <div style="font-weight:bold;font-size:10px;">Remera blanca talle M</div>
                                            <div style="display:table;width:100%;font-size:9px;">
                                                <span style="display:table-cell;">2 x Gs. 25.000</span>
                                                <span style="display:table-cell;text-align:right;font-weight:bold;">Gs. 50.000</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div style="font-weight:bold;font-size:10px;">Pantalón negro talle L</div>
                                            <div style="display:table;width:100%;font-size:9px;">
                                                <span style="display:table-cell;">1 x Gs. 80.000</span>
                                                <span style="display:table-cell;text-align:right;font-weight:bold;">Gs. 80.000</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="background:#f5f5f5;padding:8px;margin-bottom:10px;">
                                        <div style="display:table;width:100%;margin-bottom:2px;font-size:10px;">
                                            <span style="display:table-cell;">Subtotal:</span>
                                            <span style="display:table-cell;text-align:right;font-weight:bold;">Gs. 130.000</span>
                                        </div>
                                        <div style="display:table;width:100%;margin-bottom:2px;font-size:10px;">
                                            <span style="display:table-cell;">IVA (10%):</span>
                                            <span style="display:table-cell;text-align:right;font-weight:bold;">Gs. 11.818</span>
                                        </div>
                                        <div style="display:table;width:100%;border-top:2px solid #333;padding-top:4px;font-size:12px;font-weight:bold;">
                                            <span style="display:table-cell;">TOTAL:</span>
                                            <span style="display:table-cell;text-align:right;">Gs. 130.000</span>
                                        </div>
                                    </div>

                                    <div style="border:1px dashed #333;padding:8px;margin-bottom:10px;font-size:10px;">
                                        <div style="text-align:center;font-weight:bold;text-decoration:underline;margin-bottom:6px;">INFORMACIÓN DE PAGO</div>
                                        <div style="display:table;width:100%;margin-bottom:2px;">
                                            <span style="display:table-cell;">Método:</span>
                                            <span style="display:table-cell;text-align:right;font-weight:bold;">Efectivo</span>
                                        </div>
                                        <div style="display:table;width:100%;margin-bottom:2px;">
                                            <span style="display:table-cell;">Recibido:</span>
                                            <span style="display:table-cell;text-align:right;font-weight:bold;">Gs. 150.000</span>
                                        </div>
                                        <div style="display:table;width:100%;">
                                            <span style="display:table-cell;">Cambio:</span>
                                            <span style="display:table-cell;text-align:right;font-weight:bold;">Gs. 20.000</span>
                                        </div>
                                    </div>

                                    <div style="text-align:center;font-size:8px;border-top:1px dashed #333;padding-top:8px;">
                                        <div style="font-weight:bold;margin-bottom:4px;">¡Gracias por su compra!</div>
                                        <div x-show="s.footer_text" x-text="s.footer_text" style="font-style:italic;margin-bottom:4px;"></div>
                                        <div style="color:#666;">{{ now()->format('d/m/Y H:i:s') }}</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="text-center mt-2">
                            <small class="text-muted">
                                <i class="bi bi-eye me-1"></i>Vista previa en tiempo real — datos de ejemplo ilustrativos
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
