<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('submissions.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('submissions.title_description') }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('create submissions')
            <flux:button href="{{ route('admin.submissions.create') }}" variant="primary" icon="plus">
                {{ __('submissions.create_submission') }}
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
                <x-table.heading>{{ __('submissions.logo') }}</x-table.heading>
                <x-table.heading>{{ __('submissions.submission_name') }}</x-table.heading>
                <x-table.heading>{{ __('submissions.email') }}</x-table.heading>
                <x-table.heading>{{ __('submissions.company') }}</x-table.heading>
                <x-table.heading class="text-right">{{ __('global.actions') }}</x-table.heading>
            </x-table.row>
        </x-slot:head>
        <x-slot:body>
            @foreach($submissions as $submission)
            <x-table.row wire:key="submission-{{ $submission->id }}">
                <x-table.cell>{{ $submission->id }}</x-table.cell>
                <x-table.cell>
                    @if($submission->logo)
                    <img src="{{ $submission->logo_thumbnail_url }}" alt="{{ $submission->name }}" class="h-10 w-10 object-cover rounded">
                    @else
                    <div class="h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                        <flux:icon name="photo" class="h-5 w-5 text-gray-400" />
                    </div>
                    @endif
                </x-table.cell>
                <x-table.cell>{{ $submission->name }}</x-table.cell>
                <x-table.cell>{{ $submission->email }}</x-table.cell>
                <x-table.cell>{{ $submission->company->name }}</x-table.cell>
                <x-table.cell class="gap-2 flex justify-end">
                    @can('view submissions')
                    <flux:button href="{{ route('admin.submissions.show', $submission) }}" size="sm" variant="ghost">
                        {{ __('global.view') }}
                    </flux:button>
                    @endcan

                    @can('update submissions')
                    <flux:button href="{{ route('admin.submissions.edit', $submission) }}" size="sm">
                        {{ __('global.edit') }}
                    </flux:button>
                    @endcan

                    @can('delete submissions')
                    <flux:modal.trigger name="delete-submission-modal">
                        <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $submission->id }})">
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
        {{ $submissions->links() }}
    </div>

    <!-- Modal chung cho tất cả submissions -->
    <flux:modal name="delete-submission-modal"
        class="min-w-[22rem] space-y-6 flex flex-col justify-between">
        <div>
            <flux:heading size="lg">{{ __('submissions.delete_submission') }}?</flux:heading>
            <flux:subheading>
                <p>{{ __('submissions.you_are_about_to_delete') }}</p>
                <p>{{ __('global.this_action_is_irreversible') }}</p>
            </flux:subheading>
        </div>
        <div class="flex gap-2 !mt-auto mb-0">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('global.cancel') }}</flux:button>
            </flux:modal.close>
            <flux:spacer />
            <flux:button type="button" variant="danger" wire:click="deleteSubmission">
                {{ __('submissions.delete_submission') }}
            </flux:button>
        </div>
    </flux:modal>
</section>