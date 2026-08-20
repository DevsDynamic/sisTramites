@props([
    'class' => 'mb-4',
])

<div {{ $attributes->class(['card', $class]) }}>
    <div class="card-body">
        <div class="row align-items-end g-3">
            {{ $slot }}
        </div>
    </div>
</div>
