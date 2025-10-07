<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            {{ __('roles.edit_role') }}
        </x-slot:title>
        <x-slot:subtitle>
            {{ __('roles.edit_role_description') }}
        </x-slot:subtitle>

    </x-page-heading>

    <x-form wire:submit="editRole" class="space-y-6">
        <x-input wire:model.live="name" label="{{ __('roles.name') }}" />

        <div class="space-y-6">
            <x-button type="button" wire:click="selectAllPermissions" class="btn-ghost" class="btn-sm">
                {{ __('roles.select_all_permissions') }}
            </x-button>

            @php
            $permissionGroups = $permissions->groupBy(function($permission) {
            return explode(' ', $permission->name)[1] ?? 'other';
            });
            @endphp

            @foreach($permissionGroups as $group => $groupPermissions)
            <div class="space-y-3">
                <div class="flex items-center gap-x-3">
                    <h3 class="text-lg font-medium capitalize">{{ Str::ucfirst($group) }}</h3>
                </div>

                <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-3">
                    @foreach($groupPermissions as $permission)
                    <x-checkbox wire:model.live="selectedPermissions"
                        label="{{$permission->name}}"
                        value="{{$permission->id}}" />
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <x-button type="submit" icon="save" class="btn-primary">
            {{ __('roles.update_role') }}
        </x-button>
    </x-form>
</section>