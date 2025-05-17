@extends('layouts.main')

@section('content')
    <style>
        @media print {
            @page {
                size: 80mm auto;
                /* ancho fijo, alto dinámico */
                margin: 0;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                width: 80mm;
                background: white;
                font-size: 12px;
                font-family: 'Courier New', monospace;
                color: black;
            }

            /* Oculta todo menos la factura */
            body * {
                visibility: hidden !important;
            }

            #printout,
            #printout * {
                visibility: visible !important;
            }

            #printout {
                position: fixed;
                top: 0;
                left: 0;
                width: 80mm;
                padding: 0;
                margin: 0;
                background: white;
                box-sizing: border-box;
            }

            /* Evita que elementos se rompan en varias páginas */
            .invoice-box,
            table,
            tr,
            td,
            th {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            header,
            footer,
            .footer,
            .main-footer,
            .no-print {
                display: none !important;
            }
        }

        /* Estilo normal (pantalla) */
        .invoice-box {
            width: 80mm;
            margin: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 10px;
            color: #000;
            background: white;
        }

        .invoice-box h2,
        .invoice-box h4 {
            text-align: center;
            margin: 5px 0;
        }

        .invoice-box .d-flex {
            display: flex;
            justify-content: space-between;
        }

        .invoice-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-box td,
        .invoice-box th {
            padding: 2px 0;
        }

        .invoice-box small {
            color: #444;
        }
    </style>

    @if(session('success'))
        <script>
            alert_toast("{{ session('success') }}", 'success');
        </script>
    @endif
    <div class="card card-outline card-navy rounded-0 shadow">
        <div class="card-header no-print">
            <h4 class="card-title">Detalles de la Venta: <b>{{ $sale->code }}</b></h4>
            <div class="card-tools">
                <a href="{{ route('admin.sales.index') }}" class="btn btn-default border btn-sm">
                    <i class="fa fa-angle-left"></i> Volver a la Lista
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="container-fluid row justify-content-center" id="printout">
                <div class="invoice-box">
                    <h2>RESTAURANTE "LA HUELLA"</h2>
                    <div class="d-flex"><strong>Factura N°:</strong> <span>{{ $sale->code }}</span></div>
                    <div class="d-flex"><strong>Fecha:</strong> <span>{{ $sale->created_at->format('Y-m-d h:i A') }}</span>
                    </div>
                    <div class="d-flex"><strong>Nombre del Cliente:</strong> <span>{{ $sale->client_name }}</span></div>
                    <hr>

                    <table>
                        <thead>
                            <tr class="border-bottom">
                                <th style="text-align: left;">Producto</th>
                                <th style="text-align: center;">Cant.</th>
                                <th class="text-center">Precio</th>
                                <th style="text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->products as $item)
                                <tr>
                                    <td>
                                        {{ $item->product->name }}
                                    </td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-center">
                                        <small>{{ number_format($item->price, 2) }}</small>
                                    </td>
                                    <td style="text-align: right;">{{ number_format($item->qty * $item->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>
                    <div class="d-flex"><strong>Total:</strong> <span>{{ number_format($sale->amount, 2) }}</span></div>
                    <div class="d-flex"><strong>Monto entregado:</strong>
                        <span>{{ number_format($sale->tendered, 2) }}</span>
                    </div>
                    <div class="d-flex"><strong>Cambio:</strong>
                        <span>{{ number_format($sale->tendered - $sale->amount, 2) }}</span>
                    </div>
                    <div class="d-flex"><strong>Pago con:</strong>
                        <span>
                            @php
                                $types = [1 => 'Efectivo', 2 => 'Tarjeta Débito', 3 => 'Tarjeta Crédito'];
                            @endphp
                            {{ $types[$sale->payment_type] ?? 'N/A' }}
                        </span>
                    </div>
                    @if($sale->payment_type > 1)
                        <div class="d-flex"><strong>Código Pago:</strong> <span>{{ $sale->payment_code }}</span></div>
                    @endif
                    <hr>
                    <div class="d-flex">
                        <strong>Atendido por:</strong>
                        <span>{{ ucwords($sale->user->name) }}</span>
                    </div>
                    <h3 class="text-bold mt-5 text-center">¡Gracias por su compra!</h3>
                </div>
            </div>

            <hr class="no-print">
            <div class="row justify-content-around no-print ">
                <a class="btn btn-primary border col-lg-3 col-md-4 col-sm-12 col-xs-12 rounded-pill"
                    href="{{ route('admin.sales.form', $sale->id) }}"><i class="fa fa-edit"></i> Editar Venta</a>
                <button
                    class="btn btn-secondary bg-gradient-secondary border col-lg-3 col-md-4 col-sm-12 col-xs-12 rounded-pill"
                    onclick="window.print()">
                    <i class="fa fa-print"></i> Imprimir
                </button>
                <button class="btn btn-danger bg-gradient-danger border col-lg-3 col-md-4 col-sm-12 col-xs-12 rounded-pill"
                    id="delete_sale" data-id="{{ $sale->id }}">
                    <i class="fa fa-trash"></i> Eliminar venta
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#delete_sale').click(function () {
                const saleId = $(this).data('id');
                _conf("¿Está seguro de eliminar esta venta permanentemente?", "delete_sale", [saleId]);
            });
        });
        function delete_sale(id) {
            start_loader();
            $.ajax({
                url: "/admin/ventas/delete",
                method: "POST",
                data: { id: id },
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                dataType: "json",
                error: err => {
                    console.log(err)
                    alert_toast("An error occured.", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (typeof resp == 'object' && resp.status == 'success') {
                        location.replace('/admin/ventas');
                    } else {
                        alert_toast("An error occured.", 'error');
                        end_loader();
                    }
                }
            })
        }
    </script>
@endsection