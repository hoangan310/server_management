<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <ul class="menu">
            <ul class="menu".item href="{{ route('settings.profile') }}" wire:navigate>{{ __('settings.profile') }}</ul class="menu".item>
            <ul class="menu".item href="{{ route('settings.password') }}" wire:navigate>{{ __('settings.password') }}</ul class="menu".item>
            <ul class="menu".item href="{{ route('settings.appearance') }}" wire:navigate>{{ __('settings.appearance') }}</ul class="menu".item>
            <ul class="menu".item href="{{ route('settings.locale') }}" wire:navigate>{{ __('settings.locale') }}</ul class="menu".item>
        </ul class="menu">
    </div>

    <div class="divider" class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <h1>{{ $heading ?? '' }}</h1>
        <p>{{ $subheading ?? '' }}</p>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>