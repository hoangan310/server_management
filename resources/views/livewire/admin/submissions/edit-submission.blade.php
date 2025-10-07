<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('submissions.edit_submission') }}</x-slot:title>
        <x-slot:subtitle>{{ __('submissions.edit_submission_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="updateSubmission" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-input wire:model.live="name" label="{{ __('submissions.submission_name') }}" />
            <x-input wire:model.live="email" type="email" label="{{ __('submissions.email') }}" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-select wire:model.live="company_id" label="{{ __('submissions.company') }}" placeholder="{{ __('submissions.select_company') }}">
                @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
            </x-select>

            <x-select wire:model.live="category_id" label="{{ __('submissions.category') }}" placeholder="{{ __('submissions.select_category') }}">
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
            </x-select>
        </div>

        <div class="space-y-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text">{{ __('submissions.logo') }}</span>
                </label>
                <input type="file" wire:model="logo" accept="image/*" class="file-input file-input-bordered w-full" />

                @if($logo)
                <div class="mt-2 flex items-center space-x-2">
                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="h-20 w-20 object-cover rounded">
                    <x-button type="button" wire:click="removeLogo" class="btn-error" class="btn-sm">
                        {{ __('submissions.remove_logo') }}
                    </x-button>
                </div>
                @elseif($currentLogo && !$shouldRemoveLogo)
                <div class="mt-2 flex items-center space-x-2">
                    <img src="{{ $submission->logo_url }}" alt="Current logo" class="h-20 w-20 object-cover rounded">
                    <x-button type="button" wire:click="removeLogo" class="btn-error" class="btn-sm">
                        {{ __('submissions.remove_logo') }}
                    </x-button>
                </div>
                @endif
            </div>
        </div>

        <div class="form-control">
            <label class="label">
                <span class="label-text">{{ __('submissions.message') }}</span>
            </label>
            <textarea wire:model.live="message" rows="4" placeholder="{{ __('submissions.message_placeholder') }}" class="textarea textarea-bordered w-full"></textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <x-button type="button" wire:click="$dispatch('cancel')" class="btn-ghost">
                {{ __('global.cancel') }}
            </x-button>
            <x-button type="submit" icon="save" class="btn-primary">
                {{ __('submissions.update_submission') }}
            </x-button>
        </div>
    </x-form>
</section>