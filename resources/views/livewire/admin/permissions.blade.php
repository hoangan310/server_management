<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('permissions.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('permissions.title_description') }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('create permissions')
            <x-button href="{{ route('admin.permissions.create') }}" class="btn-primary" icon="o-plus">
                {{ __('permissions.create_permission') }}
            </x-button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <x-input wire:model.live="search" placeholder="{{ __('global.search_here') }}" class="!w-auto" />
        <div class="flex-1" />

        <x-select wire:model.live="perPage" class="!w-auto" :options="[
            ['id' => '10', 'name' => __('global.10_per_page')],
            ['id' => '25', 'name' => __('global.25_per_page')],
            ['id' => '50', 'name' => __('global.50_per_page')],
            ['id' => '100', 'name' => __('global.100_per_page')]
        ]" />
    </div>

    <x-table :headers="[
        ['key' => 'id', 'label' => __('global.id')],
        ['key' => 'name', 'label' => __('permissions.name')],
        ['key' => 'actions', 'label' => __('global.actions'), 'class' => 'text-right']
    ]" :rows="$permissions">
        @scope('cell_actions', $permission)
        <div class="space-x-2 flex justify-end">
            @can('update permissions')
            <x-button href="{{ route('admin.permissions.edit', $permission) }}" class="btn-sm">
                {{ __('global.edit') }}
            </x-button>
            @endcan
            @can('delete permissions')
            <x-button icon="o-trash"
                wire:click="confirmDelete({{ $permission->id }})"
                class="btn-ghost btn-sm text-error" />
            @endcan
        </div>
        @endscope
    </x-table>

    <div>
        {{ $permissions->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="confirmingPermissionId" :title="__('permissions.delete_permission') . '?'">
        <p>{{ __('permissions.you_are_about_to_delete') }}</p>
        <p>{{ __('global.this_action_is_irreversible') }}</p>

        <x-slot:actions>
            <x-button :label="__('global.cancel')"
                wire:click="afterDeletePermission"
                class="btn-ghost" />
            <x-button :label="__('permissions.delete_permission')"
                wire:click="deletePermission"
                class="btn-error" />
        </x-slot:actions>
    </x-modal>
</section>