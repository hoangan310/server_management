<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('submissions.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('submissions.title_description') }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('create submissions')
            <x-button href="{{ route('admin.submissions.create') }}" class="btn-primary" icon="o-plus">
                {{ __('submissions.create_submission') }}
            </x-button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <x-input wire:model.live="search" placeholder="{{ __('global.search_here') }}" class="!w-auto" />
        <div class="flex-1">
            <x-select wire:model.live="perPage" class="!w-auto" :options="[
            ['id' => '10', 'name' => __('global.10_per_page')],
            ['id' => '25', 'name' => __('global.25_per_page')],
            ['id' => '50', 'name' => __('global.50_per_page')],
            ['id' => '100', 'name' => __('global.100_per_page')]
             ]" />
        </div>

        <x-table :headers="[
        ['key' => 'id', 'label' => __('global.id')],
        ['key' => 'logo', 'label' => __('submissions.logo')],
        ['key' => 'name', 'label' => __('submissions.submission_name')],
        ['key' => 'email', 'label' => __('submissions.email')],
        ['key' => 'company', 'label' => __('submissions.company')],
        ['key' => 'actions', 'label' => __('global.actions'), 'class' => 'text-right']
    ]" :rows="$submissions">
            @scope('cell_logo', $submission)
            @if($submission->logo)
            <img src="{{ $submission->logo_thumbnail_url }}" alt="{{ $submission->name }}" class="h-10 w-10 object-cover rounded">
            @else
            <div class="h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                <x-icon name="photo" class="h-5 w-5 text-gray-400" />
            </div>
            @endif
            @endscope

            @scope('cell_company', $submission)
            {{ $submission->company->name }}
            @endscope

            @scope('cell_actions', $submission)
            <div class="gap-2 flex justify-end">
                @can('view submissions')
                <x-button href="{{ route('admin.submissions.show', $submission) }}" class="btn-sm" class="btn-ghost">
                    {{ __('global.view') }}
                </x-button>
                @endcan

                @can('update submissions')
                <x-button href="{{ route('admin.submissions.edit', $submission) }}" class="btn-sm">
                    {{ __('global.edit') }}
                </x-button>
                @endcan

                @can('delete submissions')
                <x-button icon="o-trash"
                    wire:click="confirmDelete({{ $submission->id }})"
                    class="btn-ghost btn-sm text-error" />
                @endcan
            </div>
            @endscope
        </x-table>

        <div class="mt-4">
            {{ $submissions->links() }}
        </div>

        <!-- Delete Confirmation Modal -->
        <x-modal wire:model="confirmingSubmissionId" :title="__('submissions.delete_submission') . '?'">
            <p>{{ __('submissions.you_are_about_to_delete') }}</p>
            <p>{{ __('global.this_action_is_irreversible') }}</p>

            <x-slot:actions>
                <x-button :label="__('global.cancel')"
                    wire:click="afterDeleteSubmission"
                    class="btn-ghost" />
                <x-button :label="__('submissions.delete_submission')"
                    wire:click="deleteSubmission"
                    class="btn-error" />
            </x-slot:actions>
        </x-modal>
    </div>
</section>