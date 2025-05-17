@extends('layouts.main')

@section('content')
    @if(session('success'))
        <script>
            alert_toast("{{ session('success') }}", 'success');
        </script>
    @endif

    @php
        $logoPath = $settings['logo'] ?? null;
        $logoSrc = $logoPath ? asset('storage/' . $logoPath) : asset('dist/img/no-image-available.png');

        $coverPath = $settings['cover'] ?? null;
        $coverSrc = $coverPath ? asset('storage/' . $coverPath) : asset('dist/img/no-image-available.png');
    @endphp

    <style>
        img#cimg {
            height: 15vh;
            width: 15vh;
            object-fit: cover;
            border-radius: 100%;
        }

        img#cimg2 {
            height: 50vh;
            width: 100%;
            object-fit: contain;
        }
    </style>

    <div class="col-lg-12">
        <div class="card card-outline rounded-0 card-navy">
            <div class="card-header">
                <h5 class="card-title">Información Del Sistema</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.system.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nombre del Sistema</label>
                        <input type="text" class="form-control form-control-sm" name="name" id="name"
                            value="{{ old('name', $settings['name'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label for="short_name">Nombre corto del sistema</label>
                        <input type="text" class="form-control form-control-sm" name="short_name" id="short_name"
                            value="{{ old('short_name', $settings['short_name'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label for="logo">Logo del Sistema</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile1" name="logo"
                                onchange="displayImg(this, $(this))" accept=".png">
                            <label class="custom-file-label" for="customFile1">Elegir Imagen en PNG</label>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <img src="{{ asset(isset($settings['logo']) && $settings['logo'] ? 'uploads/' . $settings['logo'] : 'dist/img/no-image-available.png') }}"
                            alt="Logo" id="cimg" class="img-fluid img-thumbnail">

                    </div>

                    <div class="form-group">
                        <label for="cover">Portada Del Sitio</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile2" name="cover"
                                onchange="displayImg2(this, $(this))" accept=".png,.jpg,.jpeg">
                            <label class="custom-file-label" for="customFile2">Elegir Imagen</label>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <img src="{{ asset(isset($settings['cover']) && $settings['cover'] ? 'uploads/' . $settings['cover'] : 'dist/img/no-image-available.png') }}"
                            alt="Logo" id="cimg2" class="img-fluid img-thumbnail">
                    </div>

                    <div class="form-group">
                        <label for="banners"> Imagenes del baner</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile3" name="banners[]" multiple
                                accept=".png,.jpg,.jpeg" onchange="displayImg3(this, $(this))">
                            <label class="custom-file-label" for="customFile3">Elegir Imagenes</label>
                        </div>
                        <small><i>Elija cargar nuevas imágenes de banner</i></small>
                    </div>

                    <div id="banner-list">
                        @foreach($bannerFiles as $banner)
                            <div class="d-flex w-100 align-items-center img-item mb-2">
                                <span>
                                    <img src="{{ asset("uploads/banner/" . $banner) }}" width="150px" height="100px"
                                        style="object-fit:cover;" class="img-thumbnail" alt="">
                                </span>
                                <span class="ml-4">
                                    <button class="btn btn-sm btn-default text-danger rem_img" type="button"
                                        data-path="{{ $banner }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </span>
                            </div>
                        @endforeach
                    </div>


                    <div class="card-footer">
                        <div class="col-md-12">
                            <div class="row">
                                <button class="btn btn-sm btn-primary" type="submit"
                                    onclick="start_loader();">Actualizar</button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function displayImg(input, _this) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#cimg').attr('src', e.target.result);
                    _this.siblings('.custom-file-label').html(input.files[0].name)
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function displayImg2(input, _this) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    _this.siblings('.custom-file-label').html(input.files[0].name)
                    $('#cimg2').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function displayImg3(input, _this) {
            var fnames = [];
            Object.keys(input.files).map(function (k) {
                fnames.push(input.files[k].name)
            })
            _this.siblings('.custom-file-label').html(fnames.join(", "))
        }

        function delete_img(path) {
            start_loader();
            console.log(path);
            $.ajax({
                url: "{{ route('admin.system.delete_image') }}",
                data: { path: path, _token: '{{ csrf_token() }}' },
                method: 'POST',
                dataType: "json",
                error: err => {
                    console.log(err);
                    alert_toast("An error occurred while deleting an Image", "error");
                    end_loader();
                },
                success: function (resp) {
                    if (resp.status == 'success') {
                        $('.modal').modal('hide')
                        $('[data-path="' + path + '"]').closest('.img-item').hide('slow', function () {
                            $(this).remove();
                        });
                        alert_toast("Image Successfully Deleted", "success");
                    } else {
                        alert_toast("An error occurred while deleting an Image", "error");
                    }
                    end_loader();
                }
            });
        }

        $(document).ready(function () {
            $('.rem_img').click(function () {
                let path = $(this).attr('data-path');
                _conf("Are you sure to delete this image permanently?", 'delete_img', ["'" + path + "'"])
            });

            $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ol', 'ul', 'paragraph', 'height']],
                    ['table', ['table']],
                    ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection