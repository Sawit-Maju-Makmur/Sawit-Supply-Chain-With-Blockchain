@php
    $u = Auth::user();
    $roleVal = $u ? \App\Http\Middleware\EnsureUserHasRole::roleOf($u) : null;
    $isManagement = $u && ($roleVal === 'management' || (method_exists($u, 'hasRole') && $u->hasRole('management')));
    $isOfficer = $u && in_array($roleVal, ['sustainability_officer', 'officer'], true) || ($u && method_exists($u, 'hasAnyRole') && $u->hasAnyRole(['sustainability_officer', 'officer']));
    $isPemasok = $u && ($roleVal === 'pemasok' || (method_exists($u, 'hasRole') && $u->hasRole('pemasok')));
    $isEksportir = $u && in_array($roleVal, ['eksportir_importir', 'eksportir'], true) || ($u && method_exists($u, 'hasAnyRole') && $u->hasAnyRole(['eksportir_importir', 'eksportir']));
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-green-700" />
                        <span class="font-bold text-lg text-green-800 hidden md:inline">Sawit Supply Chain</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('*.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if ($isManagement || $isOfficer)
                        <x-nav-link :href="route('management.suppliers.index')" :active="request()->routeIs('management.*')">
                            {{ __('Data Pemasok') }}
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('lahan.index')" :active="request()->routeIs('lahan.*')">
                        {{ __('Data Lahan') }}
                    </x-nav-link>

                    @if ($isPemasok)
                        <x-nav-link :href="route('land-areas.create')" :active="request()->routeIs('land-areas.*')">
                            {{ __('Pemetaan Batas') }}
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('assessments.index')" :active="request()->routeIs('assessments.*')">
                        {{ __('Self-Assessment') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown / Auth Menu -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-900 hover:border-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div class="text-left">
                                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                                    @if ($roleVal)
                                        <span class="ms-1.5 text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-800 font-normal">
                                            {{ ucfirst(str_replace('_', ' ', $roleVal)) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="ms-2">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-red-600 hover:bg-red-50">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-green-700 px-3 py-2">Log in</a>
                    <a href="{{ route('register') }}" class="ms-2 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 px-4 py-2 rounded-md">Register</a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('*.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if ($isManagement || $isOfficer)
                <x-responsive-nav-link :href="route('management.suppliers.index')" :active="request()->routeIs('management.*')">
                    {{ __('Data Pemasok') }}
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('lahan.index')" :active="request()->routeIs('lahan.*')">
                {{ __('Data Lahan') }}
            </x-responsive-nav-link>

            @if ($isPemasok)
                <x-responsive-nav-link :href="route('land-areas.create')" :active="request()->routeIs('land-areas.*')">
                    {{ __('Pemetaan Batas') }}
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('assessments.index')" :active="request()->routeIs('assessments.*')">
                {{ __('Self-Assessment') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    @if ($roleVal)
                        <div class="mt-1 text-xs text-green-700 font-semibold">{{ ucfirst(str_replace('_', ' ', $roleVal)) }}</div>
                    @endif
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-red-600">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 py-2 space-y-2">
                    <a href="{{ route('login') }}" class="block text-base font-medium text-gray-600 hover:text-gray-800">Log in</a>
                    <a href="{{ route('register') }}" class="block text-base font-medium text-green-700 hover:text-green-800">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
