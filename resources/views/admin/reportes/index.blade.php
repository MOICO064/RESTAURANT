@extends('layouts.main')

@section('content')
    @if (session('success'))
        <script>
            alert_toast("{{ session('success') }}", 'success');
        </script>
    @endif

    <div class="card card-outline rounded-0 card-navy">
        <div class="card-header">
            <h3 class="card-title">Reporte Diario de Ventas</h3>
        </div>
        <div class="card-body">
            <form id="filter-form" class="no-print">
                <div class="row">
                    <div class="col-md-4">
                        <label for="date">Fecha</label>
                        <input type="date" name="date" class="form-control" value="{{ $date }}" required>
                    </div>
                    @if (!auth()->user()->hasRole('Cajero'))
                        <div class="col-md-4">
                            <label for="user_id">Usuario</label>
                            <select name="user_id" class="form-control select2">
                                <option value="0" {{ $user_id == 0 ? 'selected' : '' }}>Todos</option>
                                @foreach ($users as $id => $name)
                                    <option value="{{ $id }}" {{ $user_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif



                    <div class="col-md-4">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <button type="button" class="btn btn-light border" id="print">
                                <i class="fa fa-print"></i> Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div id="printout" class="mt-4">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha Actualización</th>
                            <th>Código Venta</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($query as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->updated_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ $row->code }}</td>
                                <td>{{ $row->client_name }}</td>
                                <td>{{ ucfirst($users[$row->user_id] ?? 'N/A') }}</td>
                                <td class="text-end">{{ number_format($row->amount, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">TOTAL</th>
                            <th class="text-end">{{ number_format($total, 2, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <noscript id="print-header">
        <div class="container mb-4">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <img src="{{ asset('uploads/logo.png') }}" alt="Logo" style="height: 80px;">
                </div>
                <div class="col-md-10">
                    <h1 class="mb-1">{{ system_info('name') }}</h1>
                    <h3 class="mb-1 font-weight-bold">Reporte Diario de Ventas</h3>
                    <p class="mb-0"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($date)->format('d \d\e F \d\e Y') }}
                    </p>
                </div>
            </div>
            <hr class="my-3">
        </div>
    </noscript>


@endsection

@section('scripts')
    <style media="print">
        body {
            margin: 25mm 20mm;
            font-size: 14px;
            color: #000;
        }

        .no-print {
            display: none;
        }

        table {
            width: 100%;
            border-collapse: collapse !important;
        }

        table th,
        table td {
            border: 1px solid #000 !important;
            padding: 6px 8px;
        }

        h3,
        h4,
        h5 {
            margin: 0;
        }

        .text-end {
            text-align: right !important;
        }
    </style>

    <script>
        $(document).ready(function () {
            $('.select2').select2();

            $('#filter-form').submit(function (e) {
                e.preventDefault();
                const params = $(this).serialize();
                window.location.href = `?${params}`;
            });

            $('#print').click(function () {
                const head = $('head').clone();
                const content = $('#printout').clone();
                const header = $($('#print-header').html()).clone();
                const printWindow = window.open('', '', 'width=900,height=900');

                printWindow.document.write(`
                                    <html>
                                        <head>${head.html()}</head>
                                        <body>
                                            ${header.prop('outerHTML')}
                                            ${content.prop('outerHTML')}
                                        </body>
                                    </html>
                                `);
                printWindow.document.close();

                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 600);
            });
        });
    </script>
@endsection