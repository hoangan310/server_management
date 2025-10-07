<div class="flex flex-col gap-6">
    <x-auth-header :title="__('global.log_into_your_account')"
        :description="__('global.log_into_your_account_text')" />
    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />
    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <x-input wire:model="email"
            :label="__('global.email_address')"
            type="email"
            name="email"
            required
            autocomplete="email"
            placeholder="email@example.com" />
        <!-- Password -->
        <x-input wire:model="password"
            :label="__('global.password')"
            :type="$this->passwordVisible ? 'text' : 'password'"
            name="password"
            required
            autocomplete="current-password"
            placeholder="Password">
            <x-slot name="iconTrailing">
                <x-button size="sm"
                    variant="ghost"
                    :icon="$this->passwordVisible ? 'o-eye-slash' : 'o-eye'"
                    class="btn-ghost btn-sm"
                    wire:click.prevent="$toggle('passwordVisible')" />
            </x-slot>
        </x-input>
        @if (Route::has('password.request'))
        <x-text-link class="text-sm link-primary" href="{{ route('password.request') }}" wire:navigate>
            {{ __('global.forgot_password') }}
        </x-text-link>
        @endif
        <!-- Remember Me -->
        <x-checkbox wire:model="remember" :label="__('global.remember_me')" />
        <div class="flex items-center justify-end">
            <x-button variant="primary" type="submit" class="btn-primary w-full">{{ __('global.log_in') }}</x-button>
        </div>
    </form>
    @if (Route::has('register'))
    <div class="space-x-1 text-center text-sm text-base-content/70">
        <span>{{ __('global.dont_have_an_account') }}</span>
        <x-text-link href="{{ route('register') }}" wire:navigate class="link-primary">
            {{ __('global.sign_up') }}
        </x-text-link>
    </div>
    @endif
</div>