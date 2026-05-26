<div class="modal modal-blur fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="createForm" method="POST" enctype="multipart/form-data" action="{{ route('tenant.signatures.store') }}">
                @csrf
                @include('tenant.signatures.partials.form', [
                    'prefix' => 'create',
                ])
            </form>
        </div>
    </div>
</div>
