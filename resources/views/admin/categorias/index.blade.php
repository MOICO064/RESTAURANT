@extends('layouts.main')

@section('content')
    @if (session('success'))
        <script>
            alert_toast("{{ session('success') }}", 'success');
        </script>
    @endif

    <div class="card card-outline rounded-0 card-navy">
        <div class="card-header">
            <h3 class="card-title">Lista De Categorias</h3>
            <div class="card-tools">
                <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary">
                    <span class="fas fa-plus"></span> Crear Nuevo
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid table-responsive">
                <table class="table table-hover table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="20%">
                        <col width="30%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha de Creación</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $i => $row)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $row->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $row->name }}</td>
                                <td>
                                    <p class="m-0 truncate-1">{{ $row->description }}</p>
                                </td>
                                <td class="text-center">
                                    @if ($row->status == 1)
                                        <span class="badge badge-success px-3 rounded-pill">Activo</span>
                                    @else
                                        <span class="badge badge-danger px-3 rounded-pill">Inavtivo</span>
                                    @endif
                                </td>
                                <td align="center">
                                    <button type="button"
                                        class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown">
                                        Accjón
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item view_data" href="javascript:void(0)" data-id="{{ $row->id }}">
                                            <span class="fa fa-eye text-dark"></span> Ver
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="{{ $row->id }}">
                                            <span class="fa fa-edit text-primary"></span> Editar
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="{{ $row->id }}">
                                            <span class="fa fa-trash text-danger"></span> Eliminar
                                        </a>
                                    </div>
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
            $('.delete_data').click(function () {
                _conf("¿Estás seguro de eliminar esta categoría de forma permanente?", "delete_category", [$(this).attr('data-id')])
            });

            $('#create_new').click(function () {
                uni_modal("<i class='fa fa-plus'></i> Crear Nueva Categoria", "/admin/categorias/form/");
            });

            $('.view_data').click(function () {
                uni_modal("<i class='fa fa-bars'></i> Detalles de la Categoria", "/admin/categorias/" + $(this).data('id') + "/view");
            });

            $('.edit_data').click(function () {
                uni_modal("<i class='fa fa-edit'></i>Actualizar Detalles de la Categoria", "/admin/categorias/form/" + $(this).data('id'));
            });

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


            $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle');
        });

        function delete_category(id) {
            $.ajax({
                url: '/admin/categorias/' + id + '/delete',
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function (response) {
                    alert_toast(response.message, 'success');
                    location.reload();
                },
                error: function () {
                    alert_toast('An error occurred.', 'error');
                }
            });
        }
    </script>

@endsection