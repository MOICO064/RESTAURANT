<div class="container-fluid">
    <form method="POST" id="category-form">
        @csrf       

        <input type="hidden" name="id" value="{{ $category->id ?? '' }}">

        <div class="form-group">
            <label for="name" class="control-label">Nombre</label>
            <input type="text" name="name" id="name" class="form-control form-control-sm rounded-0"
                value="{{ $category->name ?? '' }}" required />
        </div>

        <div class="form-group">
            <label for="description" class="control-label">Descripción</label>
            <textarea name="description" id="description" class="form-control form-control-sm rounded-0"
                required>{{ $category->description ?? '' }}</textarea>
        </div>

        <div class="form-group">
            <label for="status" class="control-label">Estado</label>
            <select name="status" id="status" class="form-control form-control-sm rounded-0" required>
                <option value="1" {{ isset($category) && $category->status == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ isset($category) && $category->status == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#category-form').submit(function (e) {
            e.preventDefault();
            let form = $(this);
            $('.err-msg').remove(); // Quita errores anteriores
            start_loader();

            $.ajax({
                url: '/admin/categorias',
                method: form.find('input[name="_method"]').val() || 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function (resp) {
                    if (resp.status === 'success') {
                        alert_toast(resp.message || 'Saved successfully', 'success');
                        location.reload();
                    } else {
                        alert_toast(resp.message || 'An error occurred', 'error');
                        end_loader();
                    }
                },
                error: function (xhr) {
                    $('.err-msg').remove();
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function (key) {
                            let input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after('<div class="text-danger err-msg">' + errors[key][0] + '</div>');
                        });
                    } else {
                        alert_toast('An unexpected error occurred', 'error');
                    }
                    end_loader();
                }
            });
        });
    });
</script>