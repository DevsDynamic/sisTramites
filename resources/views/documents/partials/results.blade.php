@include('documents.partials.cards')

@if ($documents->hasPages())
    <div id="crudPagination" class="crud-pagination mt-4">{{ $documents->links() }}</div>
@endif
