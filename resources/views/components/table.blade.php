@props(['headers' => []])

<div class="relative w-full overflow-auto rounded-md border border-border">
    <table class="w-full caption-bottom text-sm">
        @if(count($headers) > 0 || isset($thead))
            <thead class="[&_tr]:border-b bg-muted/50">
                @if(isset($thead))
                    {{ $thead }}
                @else
                    <tr class="border-b border-border transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        @foreach($headers as $header)
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                @endif
            </thead>
        @endif
        <tbody class="[&_tr:last-child]:border-0">
            {{ $slot }}
        </tbody>
    </table>
</div>
