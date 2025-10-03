<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            {{ __('roles.create_role') }}
        </x-slot:title>
        <x-slot:subtitle>
            {{ __('roles.create_role_description') }}
        </x-slot:subtitle>

    </x-page-heading>

    <x-form wire:submit="createRole" class="space-y-6">
        <flux:input wire:model.live="name" label="{{ __('roles.name') }}" />

        @php
        $permissionGroups = $permissions->groupBy(function($permission) {
        return explode(' ', $permission->name)[1] ?? 'Other';
        });
        @endphp

        @foreach($permissionGroups as $groupName => $permissions)
        <div class="mb-4">
            <h3 class="font-semibold mb-2">{{ Str::ucfirst($groupName) }}</h3>
            <div class="flex flex-wrap gap-4">
                @foreach($permissions as $permission)
                <flux:checkbox
                    wire:model="selectedPermissions"
                    label="{{ $permission->name }}"
                    value="{{ $permission->id }}" />
                @endforeach
            </div>
        </div>
        @endforeach

        <flux:button type="submit" icon="save" variant="primary">
            {{ __('roles.create_role') }}
        </flux:button>
    </x-form>
</section>