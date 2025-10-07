<div class="flex flex-col items-start">
    <x-page-heading>
        <x-slot:title>{{ __('settings.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('settings.subtitle') }}</x-slot:subtitle>
    </x-page-heading>

    <x-settings.layout :heading="__('settings.appearance')" :subheading=" __('settings.update_your_settings_appearance')">
        <div class="form-control">
            <label class="label">
                <span class="label-text">{{ __('settings.choose_theme') }}</span>
            </label>
            <div class="join w-full">
                <input class="join-item btn" type="radio" name="theme" value="light" aria-label="{{ __('settings.light') }}" />
                <input class="join-item btn" type="radio" name="theme" value="dark" aria-label="{{ __('settings.dark') }}" />
                <input class="join-item btn" type="radio" name="theme" value="system" aria-label="{{ __('settings.system') }}" />
            </div>
        </div>
    </x-settings.layout>
</div>
