<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            {{ __('roles.title') }}
        </x-slot:title>
        <x-slot:subtitle>
            {{ __('roles.title_description') }}
        </x-slot:subtitle>
        <x-slot:buttons>
            @can('create roles')
            <x-button href="{{ route('admin.roles.create') }}" class="btn-primary" icon="o-plus">
                {{ __('roles.create_role') }}
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
        ['key' => 'name', 'label' => __('roles.name')],
        ['key' => 'permissions', 'label' => __('roles.permissions')],
        ['key' => 'actions', 'label' => __('global.actions'), 'class' => 'text-right']
    ]" :rows="$roles">
        @scope('cell_permissions', $role)
        <div class="gap-2 inline-flex flex-wrap">
            @foreach($role->permissions as $permission)
            <x-badge class="btn-sm">
                {{ $permission->name }}
            </x-badge>
            @endforeach
        </div>
        @endscope

        @scope('cell_actions', $role)
        <div class="space-x-2 flex justify-end">
            @can('update roles')
            <x-button href="{{ route('admin.roles.edit', $role) }}" class="btn-sm">
                {{ __('global.edit') }}
            </x-button>
            @endcan
            @can('delete roles')
            <x-button icon="o-trash"
                wire:click="confirmDelete({{ $role->id }})"
                class="btn-ghost btn-sm text-error" />
            @endcan
        </div>
        @endscope
    </x-table>

    <div>
        {{ $roles->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="confirmingRoleId" :title="__('roles.delete_role') . '?'">
        <p>{{ __('roles.you_are_about_to_delete') }}</p>
        <p>{{ __('global.this_action_is_irreversible') }}</p>

        <x-slot:actions>
            <x-button :label="__('global.cancel')"
                wire:click="afterDeleteRole"
                class="btn-ghost" />
            <x-button :label="__('roles.delete_role')"
                wire:click="deleteRole"
                class="btn-error" />
        </x-slot:actions>
    </x-modal>
</section>