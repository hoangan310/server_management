<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('companies.view_company') }}</x-slot:title>
        <x-slot:subtitle>Viewing {{ $company->name }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('update companies')
              <flux:button icon="edit" variant="primary" href="{{ route('admin.companies.edit', $company) }}">
                {{ __('companies.edit_company') }}
              </flux:button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('companies.company_details') }}</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('companies.company_name') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('global.created_at') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->created_at->format('M d, Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('global.updated_at') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->updated_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

</section>
