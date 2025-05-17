@extends('layouts.main')

@section('content')
    @if (session('success'))
        <script>
            alert_toast("{{ session('success') }}", 'success');
        </script>
    @endif

    <style>
        .user-avatar {
            width: 3rem;
            height: 3rem;
            object-fit: scale-down;
            object-position: center center;
        }
    </style>

    <div class="card card-outline rounded-0 card-navy">
        <div class="card-header">
            <h3 class="card-title">Lista de Usuarios</h3>
            <div class="card-tools">
                <a href="{{ route('admin.usuarios.form') }}" class="btn btn-flat btn-primary">
                    <span class="fas fa-plus"></span> Crear Nuevo </a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid table-responsive">
                <table class="table table-hover table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="10%">
                        <col width="25%">
                        <col width="25%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date Updated</th>
                            <th>Avatar</th>
                            <th>Nombre Completo</th>
                            <th>Usuario</th>
                            <th>Cargo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                                <td class="text-center">
                                    <img src="{{ asset($user->avatar ?? 'dist/img/no-image-available.png') }}" alt=""
                                        class="img-thumbnail rounded-circle user-avatar">
                                </td>
                                <td>{{ $user->name }} </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    {{ $user->roles->pluck('name')->first() ?? 'Sin rol' }}
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle"
                                            data-toggle="dropdown">
                                            Accion
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item edit_data"
                                                href="{{ route('admin.usuarios.form', $user->id) }}"><span
                                                    class="fa fa-edit text-primary"></span> Edit</a>
                                            <a class="dropdown-item delete_data" data-id="{{ $user->id }}"><span
                                                    class="fa fa-trash text-danger"></span> Delete</a>
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
            $('.delete_data').click(function () {
                _conf("Esta seguro de eliminar este usuario permanentemente?", "delete_user", [$(this).attr('data-id')])
            })
            $('.table').dataTable({
                columnDefs: [
                    { orderable: false, targets: [6] }
                ],
                order: [0, 'asc']
            });
            $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
        })
        function delete_user(id) {
            start_loader();
            $.ajax({
                url: "/admin/usuario/" + id + "/delete",
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                error: err => {
                    console.log(err)
                    alert_toast("An error occured.", 'error');
                    end_loader();
                },
                success: function (resp) {

                    location.reload();

                }
            })
        }
    </script>
@endsection