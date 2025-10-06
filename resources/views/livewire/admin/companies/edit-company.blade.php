<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('companies.edit_company') }}</x-slot:title>
        <x-slot:subtitle>{{ __('companies.edit_company_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="updateCompany" class="space-y-6">
        <flux:input wire:model.live="name" label="{{ __('companies.company_name') }}" />

        <flux:button type="submit" icon="save" variant="primary">
            {{ __('companies.update_company') }}
        </flux:button>
    </x-form>

</section>
