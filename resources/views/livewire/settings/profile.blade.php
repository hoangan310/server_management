<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('settings.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('settings.subtitle') }}</x-slot:subtitle>
    </x-page-heading>

    <x-settings.layout :heading="__('settings.profile')" :subheading="__('settings.profile_description')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <x-input wire:model="name" :label="__('users.name')" type="text" name="name" required autofocus autocomplete="name" />

            <div>
                <x-input wire:model="email" :label="__('users.email')" type="email" name="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="mt-4">
                        {{ __('settings.your_email_is_unverified') }}

                        <x-text-link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                            {{ __('settings.click_here_to_request_another') }}
                        </x-text-link>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-success">
                        {{ __('settings.verification_link_sent') }}
                    </p>
                    @endif
                </div>
                @endif
            </div>

            <x-input wire:model="birthday"
                :label="__('users.birthday')"
                type="date"
                name="birthday"
                format="YYYY-MM-DD"
                autocomplete="birthday" placeholder="YYYY-MM-DD" />
            <x-input wire:model="phone" :label="__('users.phone')" type="text" name="phone" autocomplete="phone" />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <x-button class="btn-primary" type="submit" class="w-full">{{ __('global.save') }}</x-button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('global.saved') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>