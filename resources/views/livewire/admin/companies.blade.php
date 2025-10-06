<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('companies.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('companies.title_description') }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('create companies')
            <flux:button href="{{ route('admin.companies.create') }}" variant="primary" icon="plus">
                {{ __('companies.create_company') }}
            </flux:button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <flux:input wire:model.live="search" placeholder="{{ __('global.search_here') }}" class="!w-auto" />
        <flux:spacer />
        
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
                <x-table.heading>{{ __('companies.company_name') }}</x-table.heading>
                <x-table.heading class="text-right">{{ __('global.actions') }}</x-table.heading>
            </x-table.row>
        </x-slot:head>
        <x-slot:body>
            @foreach($companies as $company)
            <x-table.row wire:key="company-{{ $company->id }}">
                <x-table.cell>{{ $company->id }}</x-table.cell>
                <x-table.cell>{{ $company->name }}</x-table.cell>
                <x-table.cell class="gap-2 flex justify-end">
                    @can('view companies')
                    <flux:button href="{{ route('admin.companies.show', $company) }}" size="sm" variant="ghost">
                        {{ __('global.view') }}
                    </flux:button>
                    @endcan

                    @can('update companies')
                    <flux:button href="{{ route('admin.companies.edit', $company) }}" size="sm">
                        {{ __('global.edit') }}
                    </flux:button>
                    @endcan

                    @can('delete companies')
                    <flux:modal.trigger name="delete-category-modal">
                        <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $company->id }})">
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
        {{ $companies->links() }}
    </div>

    <!-- Modal chung cho tất cả companies -->
    <flux:modal name="delete-company-modal"
        x-show="$wire.isShowModal" 
        class="min-w-[22rem] space-y-6 flex flex-col justify-between">
        <div>
            <flux:heading size="lg">{{ __('companies.delete_company') }}?</flux:heading>
            <flux:subheading>
                <p>{{ __('companies.you_are_about_to_delete') }}</p>
                <p>{{ __('global.this_action_is_irreversible') }}</p>
            </flux:subheading>
        </div>
        <div class="flex gap-2 !mt-auto mb-0">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('global.cancel') }}</flux:button>
            </flux:modal.close>
            <flux:spacer />
            <flux:button type="button" variant="danger" wire:click="deleteCompany">
                {{ __('companies.delete_company') }}
            </flux:button>
        </div>
    </flux:modal>
</section>