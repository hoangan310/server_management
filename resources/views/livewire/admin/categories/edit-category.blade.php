<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('categories.edit_category') }}</x-slot:title>
        <x-slot:subtitle>{{ __('categories.edit_category_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="updateCategory" class="space-y-6">
        <x-input wire:model.live="name" label="{{ __('categories.category_name') }}" />
        <x-input wire:model.live="description" label="{{ __('categories.category_description') }}" />
        <x-select wire:model.live="status" label="{{ __('categories.category_status') }}" :options="[
            ['id' => '', 'name' => __('categories.select_status')],
            ['id' => 'active', 'name' => __('categories.active')],
            ['id' => 'inactive', 'name' => __('categories.inactive')]
        ]" />

        @can('update categories')
        <x-button type="submit" icon="save" class="btn-primary">
            {{ __('categories.update_category') }}
        </x-button>
        @endcan
    </x-form>

</section>