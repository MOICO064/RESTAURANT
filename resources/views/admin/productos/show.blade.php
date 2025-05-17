<style>
    #uni_modal .modal-footer {
        display: none;
    }
</style>

<div class="container-fluid">
    <dl>
        <dt class="text-muted">Category</dt>
        <dd class="pl-4">{{ $product->category_list->name ?? '' }}</dd>

        <dt class="text-muted">Product</dt>
        <dd class="pl-4">{{ $product->name }}</dd>

        <dt class="text-muted">Description</dt>
        <dd class="pl-4">{{ $product->description }}</dd>

        <dt class="text-muted">Price</dt>
        <dd class="pl-4">{{ number_format($product->price, 2) }}</dd>

        <dt class="text-muted">Estado</dt>
        <dd class="pl-4">
            @if ($product->status == 1)
                <span class="badge badge-success px-3 rounded-pill">Activo</span>
            @else
                <span class="badge badge-danger px-3 rounded-pill">Inactivo</span>
            @endif
        </dd>
    </dl>

    <div class="clear-fix my-3"></div>
    <div class="text-right">
        <button class="btn btn-sm btn-dark bg-gradient-dark btn-flat" type="button" data-dismiss="modal">
            <i class="fa fa-times"></i> Cerrar
        </button>
    </div>
</div>