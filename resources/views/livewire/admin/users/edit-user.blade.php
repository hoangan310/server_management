<section class="w-full">
    <x-page-heading>
        <x-slot:title> {{ __('users.edit_user') }}</x-slot:title>
        <x-slot:subtitle>{{ __('users.edit_user_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="updateUser" class="space-y-6">
        <x-input wire:model.live="name" label="{{ __('users.name') }}" />
        <x-input wire:model.live="email" label="{{ __('users.email') }}" />
        <x-input wire:model.live="birthday" type="date" label="{{ __('users.birthday') }}" />
        <x-input wire:model.live="phone" type="text" label="{{ __('users.phone') }}" />

        <x-select wire:model="locale"
            label="{{ __('users.select_locale') }}"
            placeholder="{{ __('users.select_locale') }}"
            name="locale"
            :options="collect($locales)->map(fn($locale, $key) => ['id' => $key, 'name' => $locale])->values()->toArray()" />

        <div class="space-y-3">
            <label class="text-sm font-medium text-gray-700">{{ __('users.roles') }}</label>
            <p class="text-sm text-gray-500">{{ __('users.roles_description') }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($roles as $role)
                <x-checkbox wire:model.live="userRoles"
                    label="{{$role->name}}"
                    value="{{$role->id}}" />
                @endforeach
            </div>
        </div>

        <x-button type="submit" icon="save" class="btn-primary">
            {{ __('users.update_user') }}
        </x-button>
    </x-form>

</section>