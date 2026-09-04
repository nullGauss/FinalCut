@props(['items' => []])

@if (count($items) > 0)
    <nav class="flex items-center gap-1.5 text-sm mb-6 overflow-x-auto">
        @foreach ($items as $index => $item)
            @if ($index > 0)
                <span class="text-ink-secondary/40 shrink-0">/</span>
            @endif

            @if (isset($item['url']) && $index < count($items) - 1)
                <a href="{{ $item['url'] }}" class="text-ink-secondary hover:text-ink transition-colors shrink-0">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="text-ink font-medium shrink-0">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
