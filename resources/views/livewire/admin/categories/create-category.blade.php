<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('categories.create_category') }}</x-slot:title>
        <x-slot:subtitle>{{ __('categories.create_category_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="createCategory" class="space-y-6">
        <flux:input wire:model.live="name" label="{{ __('categories.category_name') }}" />
        <flux:input wire:model.live="description" label="{{ __('categories.category_description') }}" />
        <flux:select wire:model.live="status" label="{{ __('categories.category_status') }}">
            <flux:select.option value="">{{ __('categories.select_status') }}</flux:select.option>
            @foreach($statuses as $status)
            <flux:select.option value="{{ $status->value }}">{{ $status->name }}</flux:select.option>
            @endforeach
        </flux:select>

        @can('create categories')
        <flux:button type="submit" icon="save" variant="primary">
            {{ __('categories.create_category') }}
        </flux:button>
        @endcan
    </x-form>

</section>