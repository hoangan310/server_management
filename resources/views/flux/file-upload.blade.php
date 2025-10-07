@php
extract(Flux::forwardedAttributes($attributes, [
'accept',
'multiple',
'maxSize',
'preview',
]));
@endphp

@props([
'accept' => null,
'multiple' => false,
'maxSize' => '10MB',
'preview' => true,
])

@php
$classes = Flux::classes()
->add('w-full')
->add('relative')
->add('border-2')
->add('border-dashed')
->add('border-zinc-300')
->add('dark:border-zinc-600')
->add('rounded-lg')
->add('p-4')
->add('text-center')
->add('hover:border-zinc-400')
->add('dark:hover:border-zinc-500')
->add('transition-colors')
->add('cursor-pointer')
->add('group')
;

[ $styleAttributes, $attributes ] = Flux::splitAttributes($attributes);
@endphp

<div
    {{ $styleAttributes->class($classes) }}
    data-flux-file-upload
    wire:ignore
    x-data="{
        isDragging: false,
        maxSize: '{{ $maxSize }}',
        accept: '{{ $accept }}',
        
        init() {
            // Drag and drop events
            this.$el.addEventListener('dragover', (e) => {
                e.preventDefault();
                this.isDragging = true;
            });
            
            this.$el.addEventListener('dragleave', (e) => {
                e.preventDefault();
                this.isDragging = false;
            });
            
            this.$el.addEventListener('drop', (e) => {
                e.preventDefault();
                this.isDragging = false;
                // Set the files directly to the input and trigger change
                this.$refs.input.files = e.dataTransfer.files;
                this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        },
        
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        isImage(file) {
            return file.type.startsWith('image/');
        }
    }"
    x-on:click="$refs.input.click()"
    :class="{ 'border-zinc-400 dark:border-zinc-500 bg-zinc-50 dark:bg-zinc-800': isDragging }">
    <input
        x-ref="input"
        type="file"
        class="sr-only"
        {{ $attributes }}
        @if($accept) accept="{{ $accept }}" @endif
        @if($multiple) multiple @endif>

    <div class="space-y-1">
        <div class="mx-auto h-8 w-8 text-zinc-400 group-hover:text-zinc-500 dark:text-zinc-500 dark:group-hover:text-zinc-400">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
        </div>
        <div class="text-sm text-zinc-600 dark:text-zinc-400">
            <span class="font-medium text-zinc-900 dark:text-zinc-100">
                {{ __('global.click_to_upload') }}
            </span>
            {{ __('global.or_drag_and_drop') }}
        </div>
        @if($accept)
        <div class="text-xs text-zinc-500 dark:text-zinc-500">
            {{ __('global.accepted_formats', ['formats' => $accept]) }}
        </div>
        @endif
        <div class="text-xs text-zinc-500 dark:text-zinc-500">
            {{ __('global.max_size', ['size' => $maxSize]) }}
        </div>
    </div>
</div>