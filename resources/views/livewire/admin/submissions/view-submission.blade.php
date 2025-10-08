<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('submissions.view_submission') }}</x-slot:title>
        <x-slot:subtitle>{{ __('submissions.submission_details') }}</x-slot:subtitle>
    </x-page-heading>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ $submission->name }}
                </h3>
                <div class="flex space-x-2">
                    <flux:button
                        wire:click="$dispatch('edit-submission', { id: {{ $submission->id }} })"
                        icon="pencil"
                        variant="secondary"
                        size="sm">
                        {{ __('submissions.edit_submission') }}
                    </flux:button>
                    <flux:button
                        wire:click="deleteSubmission"
                        icon="trash"
                        variant="danger"
                        size="sm"
                        wire:confirm="{{ __('submissions.you_are_about_to_delete') }}">
                        {{ __('submissions.delete_submission') }}
                    </flux:button>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 space-y-6">
            @if($submission->logo)
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <img src="{{ $submission->logo_url }}" alt="{{ $submission->name }}" class="h-24 w-24 object-cover rounded-lg">
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('submissions.logo') }}</h4>
                    <p class="text-sm text-gray-900 dark:text-white">{{ __('submissions.logo_uploaded') }}</p>
                </div>
            </div>
            @endif

            @if($submission->galeries && count($submission->galeries) > 0)
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">{{ __('submissions.galeries') }}</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($submission->galeries_urls as $galery)
                    <div class="group relative">
                        <img src="{{ $galery['thumbnail'] }}" alt="Galery" class="h-32 w-full object-cover rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <a href="{{ $galery['url'] }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 hover:bg-opacity-50 transition-all rounded-lg">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('submissions.submission_name') }}</h4>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $submission->name }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('submissions.email') }}</h4>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $submission->email }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('submissions.company') }}</h4>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $submission->company->name }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('submissions.category') }}</h4>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $submission->category->name }}</p>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('submissions.message') }}</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $submission->message }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('submissions.created_at') }}</h4>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $submission->created_at->format('M d, Y H:i') }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('submissions.updated_at') }}</h4>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $submission->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>