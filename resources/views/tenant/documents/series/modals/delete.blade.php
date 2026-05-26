<div class="modal modal-blur fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                {{-- <div class="modal-body text-center py-5">
                    <i class="ti ti-trash text-danger" style="font-size:70px;"></i>
                    <h3 class="mt-3">
                        Eliminar serie
                    </h3>
                    <div class="text-secondary">
                        Esta acción no se puede deshacer.
                    </div>
                </div> --}}

                <div class="modal-body text-center py-5">
                    <i class="ti ti-alert-triangle text-warning fs-1 mb-3"></i>
                    <h3>
                        ¿Desactivar serie?
                    </h3>
                    <div class="text-secondary">
                        La serie dejará de estar disponible
                        para nuevos documentos.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button class="btn btn-danger">
                        Desactivar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
