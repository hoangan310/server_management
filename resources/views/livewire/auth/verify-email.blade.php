<div class="mt-4 flex flex-col gap-6">
    <p class="text-center">
        {{ __('global.please_verify_your_email_address') }}
    </p>

    @if (session('status') == 'verification-link-sent')
    <p class="text-center font-medium text-success">
        {{ __('global.verification_link_sent') }}
    </p>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <x-button wire:click="sendVerification" class="btn-primary" class="w-full">
            {{ __('global.resend_verification_email') }}
        </x-button>

        <x-text-link class="text-sm cursor-pointer" wire:click="logout">
            {{ __('global.log_out') }}
        </x-text-link>
    </div>
</div>