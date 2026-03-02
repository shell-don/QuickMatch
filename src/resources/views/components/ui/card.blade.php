@props([
    'padding' => true,
])

<div class="bg-white rounded-lg shadow {{ $padding ? 'p-6' : '' }}">
    {{ $slot }}
</div>
