@props(['title' => '', 'description' => ''])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md hover:border-primary/20']) }}>
    @if($title || $description || isset($header))
        <div class="flex flex-col space-y-1.5 p-6">
            @if(isset($header))
                {{ $header }}
            @else
                @if($title)
                    <h3 class="font-semibold leading-none tracking-tight">{{ $title }}</h3>
                @endif
                @if($description)
                    <p class="text-sm text-muted-foreground">{{ $description }}</p>
                @endif
            @endif
        </div>
    @endif

    <div class="p-6 pt-0">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="flex items-center p-6 pt-0">
            {{ $footer }}
        </div>
    @endif
</div>
