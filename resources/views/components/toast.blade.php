{{-- SUCCESS --}}
@if (session('success'))
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:9999;">

        <div id="successToast" class="toast show border-0" role="alert">
            <div class="toast-header bg-success text-white border-0">

                <i class="ti ti-circle-check me-2"></i>

                <strong class="me-auto">
                    Correcto
                </strong>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>

            </div>

            <div class="toast-body">
                {{ session('success') }}
            </div>

        </div>
    </div>
@endif

{{-- ERROR --}}
@if (session('error'))
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:9999;">

        <div id="errorToast" class="toast show border-0" role="alert">
            <div class="toast-header bg-danger text-white border-0">

                <i class="ti ti-alert-circle me-2"></i>

                <strong class="me-auto">
                    Error
                </strong>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>

            </div>

            <div class="toast-body">
                {{ session('error') }}
            </div>

        </div>
    </div>
@endif
