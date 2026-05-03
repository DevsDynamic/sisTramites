<h1>Panel Admin</h1>

<h2>Crear Cliente</h2>

<form method="POST" action="/admin/tenants">
    @csrf

    <input type="text" name="name" placeholder="Nombre empresa">
    <input type="text" name="ruc" placeholder="RUC">
    <input type="text" name="domain" placeholder="cliente1.sistramites.com">

    <select name="plan_id">
        @foreach($plans as $plan)
            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
        @endforeach
    </select>

    <button type="submit">Crear</button>
</form>

<hr>

<h2>Clientes</h2>

@foreach($tenants as $tenant)
    <div>
        {{ $tenant->name }} -
        {{ $tenant->domains->first()->domain ?? '' }} -
        {{ $tenant->status ?? 'activo' }}
    </div>
@endforeach