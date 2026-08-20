@include('areas.partials.cards')

@if ($areas->hasPages())
    <div id="crudPagination" class="crud-pagination mt-4">
        {{ $areas->links() }}
    </div>
@endif
