@include('users.partials.cards')

@if ($users->hasPages())
    <div id="crudPagination" class="crud-pagination mt-4">
        {{ $users->links() }}
    </div>
@endif
