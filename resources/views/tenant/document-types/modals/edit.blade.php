<div class="modal modal-blur fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                @include('tenant.document-types.partials.form', [
                    'prefix' => 'edit',
                ])
            </form>
        </div>
    </div>
</div>
