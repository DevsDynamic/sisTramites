@props(['paginator'])

@if ($paginator->hasPages())
    <div id="crudPagination" class="crud-pagination">
        {{ $paginator->links() }}
    </div>
@endif