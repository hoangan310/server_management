<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            {{__('permissions.create_permission')}}
        </x-slot:title>
        <x-slot:subtitle>
            {{__('permissions.create_permission_description')}}
        </x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="createPermission" class="space-y-6">
        <x-input wire:model.live="name" label="{{ __('permissions.name') }}" />
        <x-button type="submit" icon="save" class="btn-primary">
            {{ __('permissions.create_permission') }}
        </x-button>
    </x-form>

</section>
