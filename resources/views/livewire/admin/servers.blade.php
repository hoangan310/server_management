<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('servers.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('servers.title_description') }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('create servers')
            <flux:button href="{{ route('admin.servers.create') }}" variant="primary" icon="plus">
                {{ __('servers.create_server') }}
            </flux:button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <flux:input wire:model.live="search" placeholder="{{ __('global.search_here') }}" class="!w-auto" />
        <flux:spacer />
        <flux:select wire:model.live="type" class="!w-auto">
            <flux:select.option value="">{{ __('servers.all_types') }}</flux:select.option>
            @foreach(['VPS', 'DEDICATED', 'CLOUD'] as $serverType)
            <flux:select.option value="{{ $serverType }}">{{ __('servers.server_types.' . $serverType) }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="status" class="!w-auto">
            <flux:select.option value="">{{ __('servers.all_statuses') }}</flux:select.option>
            @foreach(['ACTIVE', 'INACTIVE', 'MAINTENANCE', 'SUSPENDED'] as $serverStatus)
            <flux:select.option value="{{ $serverStatus }}">{{ __('servers.server_statuses.' . $serverStatus) }}</flux:select.option>
            @endforeach
        </flux:select>

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
                <x-table.heading>{{ __('servers.server_name') }}</x-table.heading>
                <x-table.heading>{{ __('servers.server_ip') }}</x-table.heading>
                <x-table.heading>{{ __('servers.server_type') }}</x-table.heading>
                <x-table.heading>{{ __('servers.server_provider') }}</x-table.heading>
                <x-table.heading>{{ __('servers.server_status') }}</x-table.heading>
                <x-table.heading class="text-right">{{ __('global.actions') }}</x-table.heading>
            </x-table.row>
        </x-slot:head>
        <x-slot:body>
            @foreach($servers as $server)
            <x-table.row wire:key="server-{{ $server->id }}">
                <x-table.cell>{{ $server->id }}</x-table.cell>
                <x-table.cell>{{ $server->name }}</x-table.cell>
                <x-table.cell>{{ $server->ip }}</x-table.cell>
                <x-table.cell>
                    {{ __('servers.server_types.' . $server->type) }}
                </x-table.cell>
                <x-table.cell>{{ $server->provider ?? '-' }}</x-table.cell>
                <x-table.cell>
                    <flux:badge size="sm" variant="solid" color="{{ __('servers.server_status_colors.' . $server->status) }}">
                        {{ __('servers.server_statuses.' . $server->status) }}
                    </flux:badge>
                </x-table.cell>
                <x-table.cell class="gap-2 flex justify-end">
                    <flux:button href="{{ route('admin.servers.view', $server) }}" size="sm" variant="ghost">
                        {{ __('global.view') }}
                    </flux:button>

                    @can('update servers')
                    <flux:button href="{{ route('admin.servers.edit', $server) }}" size="sm">
                        {{ __('global.edit') }}
                    </flux:button>
                    @endcan

                    @can('delete servers')
                    <flux:modal.trigger name="delete-server-modal">
                        <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $server->id }})">
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
        {{ $servers->links() }}
    </div>

    <!-- Modal chung cho tất cả servers -->
    <flux:modal name="delete-server-modal"
        x-show="$wire.isShowModal"
        class="min-w-[22rem] space-y-6 flex flex-col justify-between">
        <div>
            <flux:heading size="lg">{{ __('servers.delete_server') }}?</flux:heading>
            <flux:subheading>
                <p>{{ __('servers.you_are_about_to_delete') }}</p>
                <p>{{ __('global.this_action_is_irreversible') }}</p>
            </flux:subheading>
        </div>
        <div class="flex gap-2 !mt-auto mb-0">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('global.cancel') }}</flux:button>
            </flux:modal.close>
            <flux:spacer />
            <flux:button type="button" variant="danger" wire:click="deleteServer">
                {{ __('servers.delete_server') }}
            </flux:button>
        </div>
    </flux:modal>
</section>