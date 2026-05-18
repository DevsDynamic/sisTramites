<div class="modal modal-blur fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="createForm" method="POST" action="{{ route('tenant.roles.store') }}">
                @csrf
                @include('tenant.roles.partials.form', [
                    'prefix' => 'create',
                ])
            </form>
        </div>
    </div>
</div>
