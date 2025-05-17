<!-- Confirm Modal -->
<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmación</h5>
            </div>
            <div class="modal-body">
                <div id="delete_content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirm">Continuar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Uni Modal -->
<div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit"
                    onclick="$('#uni_modal form').submit()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Uni Modal Right -->
<div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="fa fa-arrow-right"></span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<!-- Viewer Modal -->
<div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <button type="button" class="btn-close" data-dismiss="modal">
                <span class="fa fa-times"></span>
            </button>
            <img src="" alt="">
        </div>
    </div>
</div>