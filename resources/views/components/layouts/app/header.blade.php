<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="navbar bg-base-100" container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="drawer-side".toggle class="lg:hidden" icon="o-bars-2" inset="left" />

            <a href="{{ route('home') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0">
                <x-app-logo class="size-8" href="#"></x-app-logo>
            </a>

            <ul class="menu menu-horizontal" class="-mb-px max-lg:hidden">
                <ul class="menu menu-horizontal".item icon="o-layout-grid" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">
                    Dashboard
                </ul class="menu menu-horizontal".item>
            </ul class="menu menu-horizontal">

            <div class="flex-1" />

            <ul class="menu menu-horizontal" class="mr-1.5 space-x-0.5 py-0!">
                <div class="tooltip" content="Search" position="bottom">
                    <ul class="menu menu-horizontal".item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" label="Search" />
                </div class="tooltip">
                <div class="tooltip" content="Repository" position="bottom">
                    <ul class="menu menu-horizontal".item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="o-folder-git-2"
                        href="https://github.com/laravel/livewire-starter-kit"
                        target="_blank"
                        label="Repository"
                    />
                </div class="tooltip">
                <div class="tooltip" content="Documentation" position="bottom">
                    <ul class="menu menu-horizontal".item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="book-open-text"
                        href="https://laravel.com/docs/starter-kits"
                        target="_blank"
                        label="Documentation"
                    />
                </div class="tooltip">
            </ul class="menu menu-horizontal">

            <!-- Desktop User Menu -->
            <div class="dropdown" position="top" align="end">
                <div class="avatar"
                    class="cursor-pointer"
                    :initials="auth()->user()->initials()"
                />

                <ul class="menu">
                    <ul class="menu".radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </ul class="menu".radio.group>

                    <ul class="menu".separator />

                    <ul class="menu".radio.group>
                        <ul class="menu".item href="/settings/profile" icon="o-cog">Settings</ul class="menu".item>
                    </ul class="menu".radio.group>

                    <ul class="menu".separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <ul class="menu".item as="button" type="submit" icon="o-arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </ul class="menu".item>
                    </form>
                </ul class="menu">
            </div class="dropdown">
        </div class="navbar bg-base-100">

        <!-- Mobile Menu -->
        <div class="drawer-side" stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="drawer-side".toggle class="lg:hidden" icon="o-x-mark" />

            <a href="{{ route('dashboard') }}" class="ml-1 flex items-center space-x-2">
                <x-app-logo class="size-8" href="#"></x-app-logo>
            </a>

            <ul class="menu" variant="outline">
                <ul class="menu".group heading="Platform">
                    <ul class="menu".item icon="o-layout-grid" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">
                        Dashboard
                    </ul class="menu".item>
                </ul class="menu".group>
            </ul class="menu">

            <div class="flex-1" />

            <ul class="menu" variant="outline">
                <ul class="menu".item icon="o-folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    Repository
                </ul class="menu".item>

                <ul class="menu".item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                    Documentation
                </ul class="menu".item>
            </ul class="menu">
        </div class="drawer-side">

        {{ $slot }}

        @fluxScripts
    </body>
</html>
