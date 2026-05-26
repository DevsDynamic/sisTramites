<div class="modal modal-blur fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="createForm" method="POST" action="{{ route('tenant.document-types.store') }}">
                @csrf
                @include('tenant.document-types.partials.form', [
                    'prefix' => 'create',
                ])
            </form>
        </div>
    </div>
</div>
