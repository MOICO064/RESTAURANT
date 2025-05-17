@extends('layouts.main')
@section('content')
    @if(session('success'))
        <script>
            alert_toast("{{ session('success') }}", 'success');
        </script>
    @endif

    <div class="card card-outline rounded-0 card-navy">
        <div class="card-header">
            <h3 class="card-title">Lista de Productos</h3>
            <div class="card-tools">
                <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary">
                    <span class="fas fa-plus"></span> Crear Nuevo
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid table-responsive">
                <table class="table table-hover table-striped table-bordered" id="list">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha de Creación</th>
                            <th>Categoría</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $i => $product)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $product->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $product->category_list->name ?? 'S/C' }}</td>
                                <td>{{ $product->name }}</td>
                                <td class="text-right">{{ $product->price }}</td>
                                <td class="text-center">
                                    @if($product->status)
                                        <span class="badge badge-success px-3 rounded-pill">Activo</span>
                                    @else
                                        <span class="badge badge-danger px-3 rounded-pill">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle"
                                            data-toggle="dropdown">
                                            Action
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item view_data" data-id="{{ $product->id }}"><span
                                                    class="fa fa-eye text-dark"></span> Ver</a>
                                            <a class="dropdown-item edit_data" data-id="{{ $product->id }}"><span
                                                    class="fa fa-edit text-primary"></span> Editar</a>
                                            <a class="dropdown-item delete_data" data-id="{{ $product->id }}"><span
                                                    class="fa fa-trash text-danger"></span> Eliminar</a>
                                        </div>
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
            
             $('#list').DataTable({
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

            $('#create_new').click(function () {
                uni_modal("Crear Nuevo Producto", "/admin/productos/form");
            });

            $('.view_data').click(function () {
                uni_modal("<i class='fa fa-bars'></i> Detalles del producto", "/admin/productos/" + $(this).data('id') + "/show");
            });

            $('.edit_data').click(function () {
                uni_modal("Actualizar Detalles del Producto", "/admin/productos/form/" + $(this).data('id'));
            });

            $('.delete_data').click(function () {
                _conf("Are you sure to delete this Product permanently?", "delete_product", [$(this).attr('data-id')])
            })
        });
        function delete_product(id) {
            start_loader();
            $.ajax({
                url: '/admin/productos/' + id + '/delete',
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function (resp) {
                    alert_toast(resp.message, 'success');

                    location.reload();
                },
                error: function (xhr) {
                    alert_toast('Ocurrió un error al eliminar.', 'error');
                    console.error(xhr.responseText);
                    end_loader();
                }
            });
        }
    </script>
@endsection