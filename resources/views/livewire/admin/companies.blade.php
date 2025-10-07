<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('companies.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('companies.title_description') }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('create companies')
            <x-button href="{{ route('admin.companies.create') }}" class="btn-primary" icon="o-plus">
                {{ __('companies.create_company') }}
            </x-button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <x-input wire:model.live="search" placeholder="{{ __('global.search_here') }}" class="!w-auto" />
        <div class="flex-1" />

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
        ['key' => 'name', 'label' => __('companies.company_name')],
        ['key' => 'actions', 'label' => __('global.actions'), 'class' => 'text-right']
    ]" :rows="$companies">
        @scope('cell_actions', $company)
        <div class="gap-2 flex justify-end">
            @can('view companies')
            <x-button href="{{ route('admin.companies.show', $company) }}" class="btn-sm" class="btn-ghost">
                {{ __('global.view') }}
            </x-button>
            @endcan

            @can('update companies')
            <x-button href="{{ route('admin.companies.edit', $company) }}" class="btn-sm">
                {{ __('global.edit') }}
            </x-button>
            @endcan

            @can('delete companies')
            <x-button icon="o-trash"
                wire:click="confirmDelete({{ $company->id }})"
                class="btn-ghost btn-sm text-error" />
            @endcan
        </div>
        @endscope
    </x-table>

    <div class="mt-4">
        {{ $companies->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="confirmingCompanyId" :title="__('companies.delete_company') . '?'">
        <p>{{ __('companies.you_are_about_to_delete') }}</p>
        <p>{{ __('global.this_action_is_irreversible') }}</p>

        <x-slot:actions>
            <x-button :label="__('global.cancel')"
                wire:click="afterDeleteCompany"
                class="btn-ghost" />
            <x-button :label="__('companies.delete_company')"
                wire:click="deleteCompany"
                class="btn-error" />
        </x-slot:actions>
    </x-modal>
</section>