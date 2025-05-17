<style>
    #uni_modal .modal-footer {
        display: none;
    }
</style>
<div class="container-fluid">
    <dl>
        <dt class="text-muted">Categoría</dt>
        <dd class="pl-4">{{ $categoria->name ?? '' }}</dd>

        <dt class="text-muted">Descripción</dt>
        <dd class="pl-4">{{ $categoria->description ?? '' }}</dd>

        <dt class="text-muted">Estado</dt>
        <dd class="pl-4">
            @if(isset($categoria->status) && $categoria->status == 1)
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