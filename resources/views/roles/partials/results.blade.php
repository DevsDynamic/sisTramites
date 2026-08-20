@include('roles.partials.cards')

@if ($roles->hasPages())
    <div id="crudPagination" class="crud-pagination mt-4">
        {{ $roles->links() }}
    </div>
@endif
