<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <h1>{{ __('settings.delete_account_title') }}</h1>
        <p>{{ __('settings.delete_account_subtitle') }}</p>
    </div>

    <x-button class="btn-error" wire:click="confirmUserDeletion">
        {{ __('settings.delete_account_title') }}
    </x-button>

    <x-modal wire:model="confirmingUserDeletion" :title="__('settings.delete_are_you_sure')" class="max-w-lg">
        <p>{{ __('settings.delete_are_you_sure_text') }}</p>

        <form wire:submit="deleteUser" class="space-y-6">
            <x-input wire:model="password"
                id="password"
                :label="__('settings.password')"
                type="password"
                name="password" />

            <x-slot:actions>
                <x-button :label="__('global.cancel')"
                    wire:click="cancelUserDeletion"
                    class="btn-ghost" />
                <x-button :label="__('settings.delete_account_title')"
                    type="submit"
                    class="btn-error" />
            </x-slot:actions>
        </form>
    </x-modal>
</section>