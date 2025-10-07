<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-base-100">
    <!-- Navbar -->
    <div class="navbar bg-base-100 border-b border-base-300">
        <div class="navbar-start">
            <div class="dropdown lg:hidden">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    @auth
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @endauth
                </ul>
            </div>
            <a href="{{ route('home') }}" class="btn btn-ghost text-xl">
                <x-app-logo class="size-8"></x-app-logo>
            </a>
        </div>

        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                @auth
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
                @endauth
            </ul>
        </div>

        <div class="navbar-end">
            @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @guest
                <x-button :label="__('global.log_in')"
                    href="{{ route('login') }}"
                    class="btn-primary" />
                @if (Route::has('register'))
                <x-button :label="__('global.register')"
                    href="{{ route('register') }}" />
                @endif
                @endguest
            </nav>
            @endif

            @auth
            @if (Session::has('admin_user_id'))
            <div class="mr-4">
                <form action="{{ route('impersonate.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit"
                        :label="__('users.stop_impersonating')"
                        class="btn-error btn-sm" />
                </form>
            </div>
            @endif

            <!-- User Menu -->
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full bg-primary text-primary-content flex items-center justify-center">
                        {{ auth()->user()->initials() }}
                    </div>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    @can('access dashboard')
                    <li><a href="{{ route('admin.index') }}">{{ __('global.admin_dashboard') }}</a></li>
                    @endcan
                    <li><a href="/settings/profile">{{ __('settings.title') }}</a></li>
                    <div class="divider my-1"></div>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left">{{ __('global.log_out') }}</button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    @include('partials.footer')

    @livewireScripts
</body>

</html>