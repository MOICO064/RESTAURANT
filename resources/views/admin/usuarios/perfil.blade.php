@extends('layouts.main')

@section('content')

    <div class="card  rounded-0 card-navy">
        <div class="card-header bg-gradient-navy text-white ">
            <h3 class="card-title">Actualizar Mis Datos</h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div id="msg"></div>
                <form action="" id="manage-user" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id ?? '' }}">
                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name ?? '' }}"
                            >
                    </div>
                    
                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" name="email" class="form-control" value="{{ $user->email ?? '' }}" 
                            autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>{{ isset($user) ? 'New ' : '' }}Contraseña</label>
                        <input type="password" name="password" class="form-control" autocomplete="off">
                        @if(isset($user))
                            <small><i>Déjelo en blanco si no desea cambiar la contraseña.</i></small>
                        @endif
                    </div>
                   
                    <div class="form-group">
                        <label>Avatar</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="img"
                                onchange="displayImg(this)">
                            <label class="custom-file-label" for="customFile">Elegir Avatar</label>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <img src="{{ isset($user->avatar) ? asset($user->avatar) : asset('dist/img/no-image-available.png') }}"
                            alt="" id="cimg" class="img-fluid img-thumbnail">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-sm btn-primary" form="manage-user">Actualizar</button>
            
        </div>
    </div>



@endsection

@section('scripts')
    <style>
        #cimg {
            height: 15vh;
            width: 15vh;
            object-fit: cover;
            border-radius: 100%;
        }
    </style>

    <script>
        function displayImg(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#cimg').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

       $('#manage-user').submit(function (e) {
    e.preventDefault();
    start_loader();
    let form = $(this)[0];
    let formData = new FormData(form);

    $.ajax({
        url: '/admin/usuario',
        data: formData,
        processData: false,
        contentType: false,
        method: 'POST',
        success: function (resp) {
            if (resp.status === 'success') {                 
                location.reload()
            } else {
                // Por si acaso, algún otro error no esperado
                $('#msg').html('<div class="alert alert-danger">Ocurrió un error inesperado.</div>');
                end_loader();
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                // Errores de validación
                let errors = xhr.responseJSON.errors;
                let html = '<div class="alert alert-danger"><ul>';
                $.each(errors, function (key, messages) {
                    $.each(messages, function(i, message){
                        html += '<li>' + message + '</li>';
                    });
                });
                html += '</ul></div>';
                $('#msg').html(html);
            } else {
                console.error(xhr.responseText);
                alert_toast("An error occurred", 'error');
            }
            end_loader();
        }
    });
});

    </script>
@endsection