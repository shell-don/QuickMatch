@props([
    'items' => [],
    'home' => true,
])

@if(count($items) > 0)
<nav class="flex items-center gap-2 text-sm mb-4" aria-label="Breadcrumb">
    @if($home)
        <a href="{{ route('home') }}" class="text-slate-400 hover:text-indigo-600 transition-colors">
            <i class="bi bi-house-door"></i>
        </a>
        <span class="text-slate-300">/</span>
    @endif
    
    @foreach($items as $index => $item)
        @if($index > 0)
            <span class="text-slate-300">/</span>
        @endif
        
        @if(isset($item['url']) && $index < count($items) - 1)
            <a href="{{ $item['url'] }}" class="text-slate-400 hover:text-indigo-600 transition-colors">
                {{ $item['label'] }}
            </a>
        @else
            <span class="text-slate-800 font-medium">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
@endif
