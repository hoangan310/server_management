<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('settings.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('settings.subtitle') }}</x-slot:subtitle>
    </x-page-heading>

    <x-settings.layout :heading="__('users.locale')" :subheading="__('users.locale_description')">
        <form wire:submit="updateLocale" class="my-6 w-full space-y-6">
            <x-select wire:model="locale" 
                      placeholder="{{ __('users.select_locale') }}" 
                      name="locale"
                      :options="collect($locales)->map(fn($locale, $key) => ['id' => $key, 'name' => $locale])->values()->toArray()" />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <x-button class="btn-primary" type="submit" class="w-full">{{ __('global.save') }}</x-button>
                </div>

                <x-action-message class="me-3" on="locale-updated">
                    {{ __('global.saved') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>

</section>
