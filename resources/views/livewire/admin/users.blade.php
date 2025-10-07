<div class="p-6">
    <x-header :title="__('users.title')" :subtitle="__('users.title_description')">
        @can('create users')
        <x-slot:actions>
            <x-button :label="__('users.create_user')"
                icon="o-plus"
                class="btn-primary"
                href="{{ route('admin.users.create') }}" />
        </x-slot:actions>
        @endcan
    </x-header>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <x-input wire:model.live="search"
            :placeholder="__('global.search_here')"
            class="!w-auto" />

        <x-select wire:model.live="role"
            class="!w-auto"
            :options="collect($roles)->map(fn($role) => ['id' => $role->name, 'name' => $role->name])->prepend(['id' => '', 'name' => __('users.all_roles')])" />

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
        ['key' => 'name', 'label' => __('users.name')],
        ['key' => 'email', 'label' => __('users.email')],
        ['key' => 'roles', 'label' => __('users.roles')],
        ['key' => 'actions', 'label' => __('global.actions'), 'class' => 'text-right']
    ]" :rows="$users">
        @scope('cell_roles', $user)
        <div class="flex gap-1 flex-wrap">
            @foreach($user->roles as $role)
            <x-badge :value="$role->name" class="badge-sm" />
            @endforeach
        </div>
        @endscope

        @scope('cell_actions', $user)
        <div class="flex gap-2 justify-end">
            <x-button icon="o-eye"
                href="{{ route('admin.users.show', $user) }}"
                class="btn-ghost btn-sm" />

            @can('impersonate')
            @if(auth()->id() !== $user->id)
            <form action="{{ route('impersonate.store', $user) }}" method="POST">
                @csrf
                <x-button type="submit"
                    icon="o-user-plus"
                    class="btn-ghost btn-sm"
                    spinner />
            </form>
            @endif
            @endcan

            @can('update users')
            <x-button icon="o-pencil"
                href="{{ route('admin.users.edit', $user) }}"
                class="btn-ghost btn-sm" />
            @endcan

            @can('delete users')
            <x-button icon="o-trash"
                wire:click="confirmDelete({{ $user->id }})"
                class="btn-ghost btn-sm text-error" />
            @endcan
        </div>
        @endscope
    </x-table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="confirmingUserId" :title="__('users.delete_user') . '?'">
        <p>{{ __('users.you_are_about_to_delete') }}</p>
        <p>{{ __('global.this_action_is_irreversible') }}</p>

        <x-slot:actions>
            <x-button :label="__('global.cancel')"
                wire:click="afterDeleteUser"
                class="btn-ghost" />
            <x-button :label="__('users.delete_user')"
                wire:click="deleteUser"
                class="btn-error" />
        </x-slot:actions>
    </x-modal>
</div>