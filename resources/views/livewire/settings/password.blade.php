<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('settings.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('settings.subtitle') }}</x-slot:subtitle>
    </x-page-heading>

    <x-settings.layout :heading="__('settings.update_password')" :subheading="__('settings.update_password_description')">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <x-input
                wire:model="current_password"
                id="update_password_current_passwordpassword"
                :label="__('settings.current_password')"
                type="password"
                name="current_password"
                required
                autocomplete="current-password"
            />
            <x-input
                wire:model="password"
                id="update_password_password"
                :label="__('settings.new_password')"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <x-input
                wire:model="password_confirmation"
                id="update_password_password_confirmation"
                :label="__('settings.confirm_new_password')"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <x-button class="btn-primary" type="submit" class="w-full">{{ __('global.save') }}</x-button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('global.saved') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
