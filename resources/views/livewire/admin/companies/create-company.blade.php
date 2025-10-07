<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('companies.create_company') }}</x-slot:title>
        <x-slot:subtitle>{{ __('companies.create_company_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="createCompany" class="space-y-6">
        <x-input wire:model.live="name" label="{{ __('companies.company_name') }}" />

        <x-button type="submit" icon="save" class="btn-primary">
            {{ __('companies.create_company') }}
        </x-button>
    </x-form>

</section>
