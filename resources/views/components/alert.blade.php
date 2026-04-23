@props([
    'variant' => 'default',
    'title' => '',
])

@php
    $baseClass = 'relative w-full rounded-lg border p-4 [&>svg~*]:pl-7 [&>svg+div]:translate-y-[-3px] [&>svg]:absolute [&>svg]:left-4 [&>svg]:top-4 [&>svg]:text-foreground';

    $variants = [
        'default' => 'bg-background text-foreground',
        'destructive' => 'border-destructive/50 text-destructive dark:border-destructive [&>svg]:text-destructive',
        'warning' => 'border-yellow-500/50 text-yellow-600 dark:text-yellow-500 [&>svg]:text-yellow-600 dark:[&>svg]:text-yellow-500',
        'success' => 'border-green-500/50 text-green-600 dark:text-green-500 [&>svg]:text-green-600 dark:[&>svg]:text-green-500',
    ];

    $variantClass = $variants[$variant] ?? $variants['default'];
@endphp

<div {{ $attributes->merge(['class' => "$baseClass $variantClass"]) }} role="alert">
    @if($title)
        <h5 class="mb-1 font-medium leading-none tracking-tight">{{ $title }}</h5>
    @endif
    <div class="text-sm [&_p]:leading-relaxed">
        {{ $slot }}
    </div>
</div>
