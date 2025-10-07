<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('categories.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('categories.title_description') }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('create categories')
            <flux:button href="{{ route('admin.categories.create') }}" variant="primary" icon="plus">
                {{ __('categories.create_category') }}
            </flux:button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <flux:input wire:model.live="search" placeholder="{{ __('global.search_here') }}" class="!w-auto" />
        <flux:spacer />

        <flux:select wire:model.live="perPage" class="!w-auto">
            <flux:select.option value="10">{{ __('global.10_per_page') }}</flux:select.option>
            <flux:select.option value="25">{{ __('global.25_per_page') }}</flux:select.option>
            <flux:select.option value="50">{{ __('global.50_per_page') }}</flux:select.option>
            <flux:select.option value="100">{{ __('global.100_per_page') }}</flux:select.option>
        </flux:select>
    </div>

    <x-table>
        <x-slot:head>
            <x-table.row>
                <x-table.heading>{{ __('global.id') }}</x-table.heading>
                <x-table.heading>{{ __('categories.category_name') }}</x-table.heading>
                <x-table.heading>{{ __('categories.category_status') }}</x-table.heading>
                <x-table.heading class="text-right">{{ __('global.actions') }}</x-table.heading>
            </x-table.row>
        </x-slot:head>
        <x-slot:body>
            @foreach($categories as $category)
            <x-table.row wire:key="category-{{ $category->id }}">
                <x-table.cell>{{ $category->id }}</x-table.cell>
                <x-table.cell>{{ $category->name }}</x-table.cell>
                <x-table.cell>
                    <flux:badge color="{{ __('categories.color_status.' . $category->status) }}">{{ Str::upper($category->status) }}</flux:badge>
                </x-table.cell>
                <x-table.cell class="gap-2 flex justify-end">
                    @can('view categories')
                    <flux:button href="{{ route('admin.categories.show', $category) }}" size="sm" variant="ghost">
                        {{ __('global.view') }}
                    </flux:button>
                    @endcan

                    @can('update categories')
                    <flux:button href="{{ route('admin.categories.edit', $category) }}" size="sm">
                        {{ __('global.edit') }}
                    </flux:button>
                    @endcan

                    @can('delete categories')
                    <flux:modal.trigger name="delete-category-modal">
                        <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $category->id }})">
                            {{ __('global.delete') }}
                        </flux:button>
                    </flux:modal.trigger>
                    @endcan
                </x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot:body>
    </x-table>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    <!-- Modal chung cho tất cả categories -->
    <flux:modal name="delete-category-modal"
        class="min-w-[22rem] space-y-6 flex flex-col justify-between">
        <div>
            <flux:heading size="lg">{{ __('categories.delete_category') }}?</flux:heading>
            <flux:subheading>
                <p>{{ __('categories.you_are_about_to_delete') }}</p>
                <p>{{ __('global.this_action_is_irreversible') }}</p>
            </flux:subheading>
        </div>
        <div class="flex gap-2 !mt-auto mb-0">
            <flux:modal.close>
                <flux:button variant="ghost" wire:click="afterDeleteCategory">{{ __('global.cancel') }}</flux:button>
            </flux:modal.close>
            <flux:spacer />
            <flux:button type="button" variant="danger" wire:click="deleteCategory">
                {{ __('categories.delete_category') }}
            </flux:button>
        </div>
    </flux:modal>
</section>