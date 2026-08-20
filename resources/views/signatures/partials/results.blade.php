@include('signatures.partials.cards')

@if ($signatures->hasPages())
    <div id="crudPagination" class="crud-pagination mt-4">
        {{ $signatures->links() }}
    </div>
@endif