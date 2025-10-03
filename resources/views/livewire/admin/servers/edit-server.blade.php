<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('servers.edit_server') }}</x-slot:title>
        <x-slot:subtitle>{{ __('servers.server_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="editServer" class="space-y-6">
        <flux:input wire:model.live="name" label="{{ __('servers.server_name') }}" />
        <flux:textarea wire:model.live="description" label="{{ __('servers.server_description') }}" />
        <flux:input wire:model.live="provider" label="{{ __('servers.server_provider') }}" />
        <flux:input wire:model.live="ip" label="{{ __('servers.server_ip') }}" />
        <flux:input wire:model.live="username" label="{{ __('servers.server_username') }}" />
        <flux:input wire:model.live="password" type="password" label="{{ __('servers.server_password') }}" />
        <flux:input wire:model.live="port" label="{{ __('servers.server_port') }}" />
        <flux:input wire:model.live="cpu" label="{{ __('servers.server_cpu') }}" />
        <flux:input wire:model.live="memory" label="{{ __('servers.server_memory') }}" />
        <flux:input wire:model.live="disk_space" label="{{ __('servers.server_disk_space') }}" />
        <flux:input wire:model.live="disk_space_left" label="{{ __('servers.server_disk_space_left') }}" />
        <flux:input wire:model.live="bandwidth" label="{{ __('servers.server_bandwidth') }}" />
        <flux:select wire:model.live="type" label="{{ __('servers.server_type') }}">
            <flux:select.option value="">{{ __('servers.none') }}</flux:select.option>
            @foreach($serverTypes as $serverType)
            <flux:select.option value="{{ $serverType->name }}">{{ __('servers.server_types.' . $serverType->name) }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="status" label="{{ __('servers.server_status') }}">
            <flux:select.option value="">{{ __('servers.none') }}</flux:select.option>
            @foreach($serverStatuses as $serverStatus)
            <flux:select.option value="{{ $serverStatus->name }}">{{ __('servers.server_statuses.' . $serverStatus->name) }}</flux:select.option>
            @endforeach
        </flux:select>
        @can('update servers')
        <flux:button type="submit" icon="save" variant="primary">
            {{ __('servers.edit_server') }}
        </flux:button>
        @endcan

        <flux:button type="button" href="{{ route('admin.servers.index') }}" icon="back">
            {{ __('global.back') }}
        </flux:button>
    </x-form>

</section>