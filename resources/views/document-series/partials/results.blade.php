@include('document-series.partials.cards')

@if ($series->hasPages())
    <div id="crudPagination" class="crud-pagination mt-4">
        {{ $series->links() }}
    </div>
@endif