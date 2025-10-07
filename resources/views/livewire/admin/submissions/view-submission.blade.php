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
                        size="sm"
                    >
                        {{ __('submissions.edit_submission') }}
                    </flux:button>
                    <flux:button 
                        wire:click="deleteSubmission" 
                        icon="trash" 
                        variant="danger" 
                        size="sm"
                        wire:confirm="{{ __('submissions.you_are_about_to_delete') }}"
                    >
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