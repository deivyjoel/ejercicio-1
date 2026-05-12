<div id="modalEstado" class="modal fade" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content bd-0">
            <div class="modal-header pd-y-20 pd-x-25">
                <h4 class="modal-title" id="lbltitulo">Cambiar Estado del Turno</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!--- Formulario turno -->
            <form method="post" id="turno_form">
                <div class="modal-body">
                    <input type="hidden" id="modal_tur_id">
                    <input type="hidden" id="modal_ser_id">
                    
                    <p>Turno: <strong id="modal_tur_codigo"></strong></p>
                    <p>Estado actual: <strong id="modal_tur_estado_actual"></strong></p>

                    <div class="mb-3">
                        <label class="form-label fw-bold" id="text_estado"></label>
                        <div id="modal_botones_estado" class="d-flex gap-2 flex-wrap"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>