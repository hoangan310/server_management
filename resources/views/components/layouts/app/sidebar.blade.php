<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-base-100">
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />

        <!-- Page content -->
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div class="navbar bg-base-100 lg:hidden">
                <div class="flex-none">
                    <label for="drawer-toggle" class="btn btn-square btn-ghost">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>
                <div class="flex-1">
                    <a href="{{ route('home') }}" class="btn btn-ghost text-xl">
                        <x-app-logo class="size-8"></x-app-logo>
                    </a>
                </div>
                <div class="flex-none">
                    @auth
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full bg-primary text-primary-content flex items-center justify-center">
                                {{ auth()->user()->initials() }}
                            </div>
                        </div>
                        <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                            <li>
                                <div class="flex items-center gap-2 px-1 py-1.5">
                                    <div class="w-8 h-8 rounded-full bg-primary text-primary-content flex items-center justify-center">
                                        {{ auth()->user()->initials() }}
                                    </div>
                                    <div>
                                        <div class="font-semibold">{{ auth()->user()->name }}</div>
                                        <div class="text-xs">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                            </li>
                            <div class="divider my-1"></div>
                            <li><a href="/settings/profile">{{ __('global.settings') }}</a></li>
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

            <!-- Main content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        <!-- Sidebar -->
        <div class="drawer-side">
            <label for="drawer-toggle" aria-label="close sidebar" class="drawer-overlay"></label>
            <aside class="min-h-full w-80 bg-base-200">
                <div class="p-4">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 mb-6">
                        <x-app-logo class="size-8"></x-app-logo>
                        <span class="font-bold text-lg">Admin Panel</span>
                    </a>

                    <div class="mb-6">
                        <x-button label="{{ __('global.go_to_frontend') }}"
                            icon="o-arrow-left"
                            href="{{ route('home') }}"
                            class="btn-sm" />
                    </div>

                    <ul class="menu p-0 w-full">
                        <li class="menu-title">
                            <span>Platform</span>
                        </li>
                        <li>
                            <a href="{{ route('admin.index') }}"
                                class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
                                <x-icon name="o-home" class="w-4 h-4" />
                                Dashboard
                            </a>
                        </li>

                        @canany(['view users', 'view roles', 'view permissions', 'view categories', 'view companies', 'view submissions'])
                        <li class="menu-title">
                            <span>Users</span>
                        </li>
                        @can('view users')
                        <li>
                            <a href="{{ route('admin.users.index') }}"
                                class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <x-icon name="o-user" class="w-4 h-4" />
                                {{ __('users.title') }}
                            </a>
                        </li>
                        @endcan
                        @can('view roles')
                        <li>
                            <a href="{{ route('admin.roles.index') }}"
                                class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <x-icon name="o-user-group" class="w-4 h-4" />
                                {{ __('roles.title') }}
                            </a>
                        </li>
                        @endcan
                        @can('view permissions')
                        <li>
                            <a href="{{ route('admin.permissions.index') }}"
                                class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                <x-icon name="o-shield-check" class="w-4 h-4" />
                                {{ __('permissions.title') }}
                            </a>
                        </li>
                        @endcan
                        @can('view categories')
                        <li>
                            <a href="{{ route('admin.categories.index') }}"
                                class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <x-icon name="o-folder-git-2" class="w-4 h-4" />
                                {{ __('categories.title') }}
                            </a>
                        </li>
                        @endcan
                        @can('view companies')
                        <li>
                            <a href="{{ route('admin.companies.index') }}"
                                class="{{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                                <x-icon name="o-building-office" class="w-4 h-4" />
                                {{ __('companies.title') }}
                            </a>
                        </li>
                        @endcan
                        @can('view submissions')
                        <li>
                            <a href="{{ route('admin.submissions.index') }}"
                                class="{{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}">
                                <x-icon name="o-document-text" class="w-4 h-4" />
                                {{ __('submissions.title') }}
                            </a>
                        </li>
                        @endcan
                        @endcanany
                    </ul>

                    @if (Session::has('admin_user_id'))
                    <div class="mt-auto p-4 bg-warning/20 rounded-lg">
                        <form action="{{ route('impersonate.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <p class="text-sm mb-2">
                                {{ __('users.you_are_impersonating') }}:
                                <strong>{{ auth()->user()->name }}</strong>
                            </p>
                            <x-button type="submit"
                                label="{{ __('users.stop_impersonating') }}"
                                class="btn-error btn-sm w-full" />
                        </form>
                    </div>
                    @endif

                    @auth
                    <div class="mt-auto">
                        <div class="dropdown dropdown-top w-full">
                            <div tabindex="0" role="button" class="btn btn-ghost w-full justify-start">
                                <div class="w-8 h-8 rounded-full bg-primary text-primary-content flex items-center justify-center">
                                    {{ auth()->user()->initials() }}
                                </div>
                                <div class="text-left">
                                    <div class="font-semibold">{{ auth()->user()->name }}</div>
                                    <div class="text-xs">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-full mb-2">
                                <li><a href="/settings/profile">{{ __('global.settings') }}</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left">{{ __('global.log_out') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endauth
                </div>
            </aside>
        </div>
    </div>

    @livewireScripts
    <x-livewire-alert::scripts />
    <x-livewire-alert::flash />

</body>

</html>