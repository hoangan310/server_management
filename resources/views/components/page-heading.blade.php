@props([
    'title' => null,
    'subtitle' => null,
])
<div class="relative mb-6 w-full">
    <div class="flex justify-between items-center @if(empty($subtitle)) mb-4 @endif">
        <div>
            @if(!empty($title))
                <h1 class="text-3xl font-bold">{{ $title }}</h1>
            @endif
            @if(!empty($subtitle))
                <p class="text-lg text-base-content/70 mb-6">{{ $subtitle }}</p>
            @endif
        </div>
        <div>
            @if(!empty($buttons))
                <div class="flex gap-2">
                    {{ $buttons }}
                </div>
            @endif
        </div>
    </div>
    <div class="divider"></div>
</div>
