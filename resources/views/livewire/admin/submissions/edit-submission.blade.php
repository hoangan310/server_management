<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('submissions.edit_submission') }}</x-slot:title>
        <x-slot:subtitle>{{ __('submissions.edit_submission_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="updateSubmission" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:input wire:model.live="name" label="{{ __('submissions.submission_name') }}" />
            <flux:input wire:model.live="email" type="email" label="{{ __('submissions.email') }}" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:select wire:model.live="company_id" label="{{ __('submissions.company') }}" placeholder="{{ __('submissions.select_company') }}">
                @foreach($companies as $company)
                <flux:select.option value="{{ $company->id }}">{{ $company->name }}</flux:option>
                    @endforeach
            </flux:select>

            <flux:select wire:model.live="category_id" label="{{ __('submissions.category') }}" placeholder="{{ __('submissions.select_category') }}">
                @foreach($categories as $category)
                <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:option>
                    @endforeach
            </flux:select>
        </div>

        <div class="space-y-4">
            <flux:field label="{{ __('submissions.logo') }}">
                <flux:file-upload wire:model="logo" accept="image/*" />

                @if($logo)
                <div class="mt-2 flex items-center space-x-2">
                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="h-20 w-20 object-cover rounded">
                    <flux:button type="button" wire:click="removeLogo" variant="danger" size="sm">
                        {{ __('submissions.remove_logo') }}
                    </flux:button>
                </div>
                @elseif($currentLogo && !$shouldRemoveLogo)
                <div class="mt-2 flex items-center space-x-2">
                    <img src="{{ $submission->logo_url }}" alt="Current logo" class="h-20 w-20 object-cover rounded">
                    <flux:button type="button" wire:click="removeLogo" variant="danger" size="sm">
                        {{ __('submissions.remove_logo') }}
                    </flux:button>
                </div>
                @endif
            </flux:field>
        </div>

        <div class="space-y-4">
            <flux:field label="{{ __('submissions.galeries') }}">
                <flux:file-upload wire:model="galeries" accept="image/*" multiple />

                {{-- Display current galeries --}}
                @if(!empty($currentGaleries))
                <div class="mt-4">
                    <h4 class="text-sm font-medium mb-2">{{ __('submissions.current_galeries') }}</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($currentGaleries as $galeryPath)
                        <div class="relative">
                            <img src="{{ app(\App\Services\ImageService::class)->url($galeryPath) }}" alt="Galery" class="h-32 w-full object-cover rounded">
                            <flux:button type="button" wire:click="removeCurrentGalery('{{ $galeryPath }}')" variant="danger" size="sm" class="mt-2 w-full">
                                {{ __('global.remove') }}
                            </flux:button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Display new galeries --}}
                @if(!empty($galeries))
                <div class="mt-4">
                    <h4 class="text-sm font-medium mb-2">{{ __('submissions.new_galeries') }}</h4>
                    <ul class="space-y-4">
                        @foreach($galeries as $index => $galery)
                        <div class="relative">
                            <img src="{{ app(\App\Services\ImageService::class)->url($galeryPath) }}" alt="Galery" class="h-32 w-full object-cover rounded">
                            <flux:button type="button" wire:click="removeCurrentGalery('{{ $galeryPath }}')" variant="danger" size="sm" class="mt-2 w-full">
                                {{ __('global.remove') }}
                            </flux:button>
                        </div>
                        @endforeach
                    </ul>
                </div>
                @endif
            </flux:field>
        </div>

        <flux:field label="{{ __('submissions.message') }}">
            <flux:textarea wire:model.live="message" rows="4" placeholder="{{ __('submissions.message_placeholder') }}" />
        </flux:field>

        <div class="flex justify-end space-x-3">
            <flux:button type="button" wire:click="$dispatch('cancel')" variant="ghost">
                {{ __('global.cancel') }}
            </flux:button>
            <flux:button type="submit" icon="save" variant="primary">
                {{ __('submissions.update_submission') }}
            </flux:button>
        </div>
    </x-form>
</section>