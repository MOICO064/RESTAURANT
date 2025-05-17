<div class="container-fluid">
    <form id="product-form" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $product->id ?? '' }}">
        <div class="form-group">
            <label for="category_id" class="control-label">Categoria</label>
            <select name="category_id" id="category_id" class="form-control form-control-sm rounded-0" required>                
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ isset($product) && $product->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Nombre</label>
            <input type="text" name="name" id="name" class="form-control form-control-sm rounded-0"
                value="{{ $product->name ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="description" class="control-label">Descripción</label>
            <textarea name="description" id="description" class="form-control form-control-sm rounded-0"
                required>{{ $product->description ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label for="price" class="control-label">Precio</label>
            <input type="number" name="price" id="price" class="form-control form-control-sm rounded-0 text-right"
                value="{{ $product->price ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="status" class="control-label">Estado</label>
            <select name="status" id="status" class="form-control form-control-sm rounded-0" required>
                <option value="1" {{ isset($product) && $product->status == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ isset($product) && $product->status == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {
        $('#category_id').select2({
            placeholder: "Seleccione Aquí",
            width: '100%',
            dropdownParent: $('#uni_modal'),
            containerCssClass: 'form-control form-control-sm rounded-0'
        });

        $('#product-form').on('submit', function (e) {
             e.preventDefault();
            let form = $(this);
            $('.err-msg').remove(); 
            start_loader();
            $.ajax({
                url: '/admin/productos',
                method: 'POST',
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