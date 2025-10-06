<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('categories.view_category') }}</x-slot:title>
        <x-slot:subtitle>Viewing {{ $category->name }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('update categories')
              <flux:button icon="edit" variant="primary" href="{{ route('admin.categories.edit', $category) }}">
                {{ __('categories.edit_category') }}
              </flux:button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('categories.category_details') }}</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('categories.category_name') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('global.created_at') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->created_at->format('M d, Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('global.updated_at') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->updated_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

</section>
