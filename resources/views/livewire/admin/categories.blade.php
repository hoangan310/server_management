<div class="p-6">
    <x-header :title="__('categories.title')" :subtitle="__('categories.title_description')">
        @can('create categories')
        <x-slot:actions>
            <x-button :label="__('categories.create_category')"
                icon="o-plus"
                class="btn-primary"
                href="{{ route('admin.categories.create') }}" />
        </x-slot:actions>
        @endcan
    </x-header>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <x-input wire:model.live="search"
            :placeholder="__('global.search_here')"
            class="!w-auto" />

        <x-select wire:model.live="perPage"
            class="!w-auto"
            :options="[
                      ['id' => '10', 'name' => __('global.10_per_page')],
                      ['id' => '25', 'name' => __('global.25_per_page')],
                      ['id' => '50', 'name' => __('global.50_per_page')],
                      ['id' => '100', 'name' => __('global.100_per_page')]
                  ]" />
    </div>

    <x-table :headers="[
        ['key' => 'id', 'label' => __('global.id')],
        ['key' => 'name', 'label' => __('categories.category_name')],
        ['key' => 'status', 'label' => __('categories.category_status')],
        ['key' => 'actions', 'label' => __('global.actions'), 'class' => 'text-right']
    ]" :rows="$categories">
        @scope('cell_status', $category)
        <x-badge :value="Str::upper($category->status)"
            :class="'badge-' . __('categories.color_status.' . $category->status)" />
        @endscope

        @scope('cell_actions', $category)
        <div class="flex gap-2 justify-end">
            @can('view categories')
            <x-button icon="o-eye"
                href="{{ route('admin.categories.show', $category) }}"
                class="btn-ghost btn-sm" />
            @endcan

            @can('update categories')
            <x-button icon="o-pencil"
                href="{{ route('admin.categories.edit', $category) }}"
                class="btn-ghost btn-sm" />
            @endcan

            @can('delete categories')
            <x-button icon="o-trash"
                wire:click="confirmDelete({{ $category->id }})"
                class="btn-ghost btn-sm text-error" />
            @endcan
        </div>
        @endscope
    </x-table>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="confirmingCategoryId" :title="__('categories.delete_category') . '?'">
        <p>{{ __('categories.you_are_about_to_delete') }}</p>
        <p>{{ __('global.this_action_is_irreversible') }}</p>

        <x-slot:actions>
            <x-button :label="__('global.cancel')"
                wire:click="afterDeleteCategory"
                class="btn-ghost" />
            <x-button :label="__('categories.delete_category')"
                wire:click="deleteCategory"
                class="btn-error" />
        </x-slot:actions>
    </x-modal>
</div>