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
        <flux:input wire:model.live="name" label="{{ __('roles.name') }}" />

        <div class="space-y-6">
            <flux:button type="button" wire:click="selectAllPermissions" variant="ghost" size="sm">
                {{ __('roles.select_all_permissions') }}
            </flux:button>

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

                <flux:checkbox.group wire:model.live="selectedPermissions" class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1">
                    @foreach($groupPermissions as $permission)
                    <flux:checkbox label="{{$permission->name}}" value="{{$permission->id}}" />
                    @endforeach
                </flux:checkbox.group>
            </div>
            @endforeach
        </div>

        <flux:button type="submit" icon="save" variant="primary">
            {{ __('roles.update_role') }}
        </flux:button>
    </x-form>
</section>