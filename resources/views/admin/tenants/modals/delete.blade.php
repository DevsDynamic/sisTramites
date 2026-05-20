<div class="modal modal-blur fade" id="deleteModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="{{ route('tenants.destroy', $tenant) }}">

                @csrf
                @method('DELETE')

                <div class="modal-header">

                    <h3 class="modal-title text-danger">

                        Eliminar Cliente

                    </h3>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-danger">

                        <div class="fw-bold mb-2">
                            Esta acción es irreversible
                        </div>

                        Se eliminará:

                        <ul class="mb-0 mt-2">

                            <li>Tenant</li>
                            <li>Dominio</li>
                            <li>Usuarios</li>
                            <li>Configuraciones</li>

                        </ul>

                    </div>

                    <p class="mb-0">

                        ¿Deseas eliminar a:

                        <strong>
                            {{ $tenant->business_name }}
                        </strong>?

                    </p>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button class="btn btn-danger">

                        <i class="ti ti-trash"></i>

                        Eliminar Definitivamente

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
