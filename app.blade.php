@if (request()->routeIs('backend.*'))
    <x-layouts.app.backend :title="$title ?? null">
        <flux:main>
            {{ $slot }}
        </flux:main>
    </x-layouts.app.backend>
@else
    <x-layouts.app.frontend :title="$title ?? null">
        <flux:main>
            {{ $slot }}
        </flux:main>
    </x-layouts.app.frontend>
@endif


//or

@php
    // Determine the layout type based on the route name
    $layout = request()->routeIs('backend.*') ? 'backend' : 'frontend';
@endphp

{{-- Dynamically render the correct layout component --}}
<x-dynamic-component :component="'layouts.app.' . $layout" :title="$title ?? null">
    <main>
        {{ $slot }}
    </main>
</x-dynamic-component>
