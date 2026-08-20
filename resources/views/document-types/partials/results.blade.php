@include('document-types.partials.cards')

{{-- <x-slot:pagination>
    <x-crud.pagination :paginator="$types" />
</x-slot:pagination> --}}

{{-- <div class="mt-4">
    <x-crud.pagination :paginator="$types" />
</div> --}}

@if ($types->hasPages())
    <div id="crudPagination" class="crud-pagination mt-4">
        {{ $types->links() }}
    </div>
@endif