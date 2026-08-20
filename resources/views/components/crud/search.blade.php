@props([
    'name' => 'search',
    'placeholder' => 'Buscar...',
    'value' => request('search'),
    'autocomplete' => 'off',
])

<div class="input-icon">
    <span class="input-icon-addon">
        <i class="ti ti-search"></i>
    </span>

    <input type="search" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
        class="form-control" autocomplete="{{ $autocomplete }}" data-crud-filter>
</div>
