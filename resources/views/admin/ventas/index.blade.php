@extends('layouts.main')

@section('content')
    @if(session('success'))
        <script>
            alert_toast("{{ session('success') }}", 'success');
        </script>
    @endif

    <div class="card card-outline rounded-0 card-navy">
        <div class="card-header">
            <h3 class="card-title">Lista de Ventas</h3>
            <div class="card-tools">
                <a href="{{ route('admin.sales.form') }}" class="btn btn-flat btn-primary">
                    <span class="fas fa-plus"></span> Crear Nuevo
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <table class="table table-hover table-striped table-bordered">
                    <colgroup>
                        <col width="5%">
                        <col width="20%">
                        <col width="20%">
                        <col width="25%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha de Actualización</th>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $i => $sale)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>
                                    <p class="m-0 truncate-1">{{ $sale->updated_at->format('M d, Y H:i') }}</p>
                                </td>
                                <td>
                                    <p class="m-0 truncate-1">{{ $sale->code }}</p>
                                </td>
                                <td>
                                    <p class="m-0 truncate-1">{{ $sale->client_name }}</p>
                                </td>
                                <td class="text-right">{{ number_format($sale->amount, 2) }}</td>
                                <td align="center">
                                    <a class="btn btn-default bg-gradient-light btn-flat btn-sm"
                                        href="{{ route('admin.sales.show', $sale->id) }}">
                                        <span class="fa fa-eye text-dark"></span> Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.table').DataTable({
                columnDefs: [
                    { orderable: false, targets: [5] }
                ],
                order: [[0, 'asc']],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna ascendente",
                        "sortDescending": ": activar para ordenar la columna descendente"
                    }
                }
            });
            $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
        });
    </script>
@endsection