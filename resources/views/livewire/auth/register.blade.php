<div class="flex flex-col gap-6">
    <x-auth-header :title="__('global.create_an_account')" :description="__('global.create_an_account_description')" />
    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />
    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <x-input wire:model="name"
            id="name"
            :label="__('users.name')"
            type="text"
            name="name"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('users.your_full_name')" />
        <!-- Email Address -->
        <x-input wire:model="email"
            id="email"
            :label="__('global.email_address')"
            type="email"
            name="email"
            required
            autocomplete="email"
            placeholder="email@example.com" />
        <!-- Password -->
        <x-input wire:model="password"
            id="password"
            :label="__('global.password')"
            :type="$this->passwordVisible ? 'text' : 'password'"
            name="password"
            required
            autocomplete="new-password"
            :placeholder="__('global.password')">
            <x-slot name="iconTrailing">
                <x-button size="sm"
                    variant="ghost"
                    :icon="$this->passwordVisible ? 'o-eye-slash' : 'o-eye'"
                    class="btn-ghost btn-sm"
                    wire:click.prevent="$toggle('passwordVisible')" />
            </x-slot>
        </x-input>
        <!-- Confirm Password -->
        <x-input wire:model="password_confirmation"
            id="password_confirmation"
            :label="__('global.confirm_password')"
            :type="$this->ConfirmationPasswordVisible ? 'text' : 'password'"
            name="password_confirmation"
            required
            autocomplete="new-password"
            :placeholder="__('global.confirm_password')">
            <x-slot name="iconTrailing">
                <x-button size="sm"
                    variant="ghost"
                    :icon="$this->ConfirmationPasswordVisible ? 'o-eye-slash' : 'o-eye'"
                    class="btn-ghost btn-sm"
                    wire:click.prevent="$toggle('ConfirmationPasswordVisible')" />
            </x-slot>
        </x-input>
        <div class="flex items-center justify-end">
            <x-button type="submit" class="btn-primary w-full"> {{ __('global.create_an_account') }} </x-button>
        </div>
    </form>
    <div class="space-x-1 text-center text-sm text-base-content/70">
        <span> {{ __('global.already_have_an_account') }} </span>
        <x-text-link href="{{ route('login') }}" wire:navigate class="link-primary"> {{ __('global.sign_in') }} </x-text-link>
    </div>
</div>