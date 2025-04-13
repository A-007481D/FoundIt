@props(['showSearch' => false])

<nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-md border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-1">
                    <img src="/logo.png" alt="Logo" class="h-8 w-8 object-cover">
                    <span class="font-bold text-xl text-purple-600">Foundit!</span>
                </a>

                @auth
                    <!-- desktop -->
                    <div class="hidden md:flex items-center ml-10 space-x-4">
                        <x-nav-link href="{{ route('discover') }}" :active="request()->routeIs('discover')">
                            Discover
                        </x-nav-link>
                        <x-nav-link href="{{ route('matches') }}" :active="request()->routeIs('matches')">
                            Matches
                        </x-nav-link>
                    </div>
                @endauth
            </div>

            <div class="flex items-center space-x-4">
                @auth
                    <div class="hidden md:flex items-center relative" x-data="{ searchOpen: false }">
                        <input x-show="searchOpen" type="text"
                               class="w-48 px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Search...">
                        <button @click="searchOpen = !searchOpen"
                                class="p-2 text-gray-600 hover:text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                @endauth

                <!-- desktop Auth links -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <!-- notifications -->
                        <button class="p-2 text-gray-600 hover:text-purple-600 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- profile dropdown -->
                        <div class="relative ml-3" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="flex items-center space-x-1 text-sm text-gray-700 hover:text-purple-600">
                                <span>{{ Auth::user()->firstname }}</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="{{ route('profile') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Log out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="rounded-md font-medium bg-white border-2 border-purple-600 text-purple-600 px-4 py-2 hover:bg-purple-50">
                            Log in
                        </a>
                        <a href="{{ route('register') }}"
                           class="rounded-md text-white font-medium bg-purple-600 hover:bg-purple-700 border-2 border-purple-600 px-4 py-2">
                            Sign up
                        </a>
                    @endauth
                </div>
            </div>

            <!-- mobile menu button -->
            <div class="-mr-2 flex md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-purple-600">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen}"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen}"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- mobile menu -->
    <div class="md:hidden" x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            @auth
                <x-nav-link href="{{ route('discover') }}" mobile>
                    Discover
                </x-nav-link>
                <x-nav-link href="{{ route('matches') }}" mobile>
                    Matches
                </x-nav-link>
                <x-nav-link href="{{ route('profile') }}" mobile>
                    Profile
                </x-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left block px-3 py-2 text-base font-medium text-gray-600 hover:text-purple-600">
                        Log out
                    </button>
                </form>
            @else
                <x-nav-link href="{{ route('login') }}" mobile>
                    Login
                </x-nav-link>
                <x-nav-link href="{{ route('register') }}" mobile>
                    Register
                </x-nav-link>
            @endauth
        </div>
    </div>
</nav>
