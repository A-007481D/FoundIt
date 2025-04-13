<header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="container mx-auto px-20 flex h-16 items-center justify-between">
        <div class="flex items-center gap-6">
            <a href="/" class="flex items-center gap-2 font-bold">
                <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <span class="text-xl">FoundIt!</span>
            </a>

            <nav class="hidden md:flex items-center gap-6">
                <a href="/feed" class="{{ Route::is('feed') ? 'text-primary' : 'text-foreground' }} text-sm font-medium hover:text-primary transition-colors">
                    Feed
                </a>
                <a href="/discover" class="{{ Route::is('discover') ? 'text-primary' : 'text-foreground' }} text-sm font-medium hover:text-primary transition-colors">
                    Discover
                </a>
                <a href="/matches" class="{{ Route::is('matches') ? 'text-primary' : 'text-foreground' }} text-sm font-medium hover:text-primary transition-colors">
                    Recent Matches
                </a>
                <a href="/messages" class="{{ Route::is('messages') ? 'text-primary' : 'text-foreground' }} text-sm font-medium hover:text-primary transition-colors">
                    Inbox
                </a>
            </nav>
        </div>

        <div class="flex items-center gap-4">
            <div class="relative hidden md:block">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-2.5 h-4 w-4 text-mutedrecent-foreground">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <input type="search" placeholder="Search..." class="w-[200px] lg:w-[300px] pl-8 py-2 px-2 rounded-full bg-mutedrecent border-none outline-none focus:ring-2 focus:ring-primary">
            </div>

            @auth
                <button class="relative p-2 rounded-full hover:bg-mutedrecent/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                    </svg>
                    <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-primary text-[10px] text-primary-foreground">
                    2
                </span>
                </button>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative h-8 w-8 rounded-full hover:ring-2 hover:ring-mutedrecent">
                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                            <span class="text-sm font-medium">{{ strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1)) }}</span>
                        </div>
                    </button>

                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium leading-none">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</p>
                                <p class="text-xs leading-none text-mutedrecent-foreground mt-1">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-mutedrecent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Profile</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-mutedrecent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <span>Inbox</span>
                                <span class="ml-auto bg-mutedrecent text-xs font-medium px-2 py-0.5 rounded">3</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-mutedrecent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                                </svg>
                                <span>Notifications</span>
                                <span class="ml-auto bg-mutedrecent text-xs font-medium px-2 py-0.5 rounded">2</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-mutedrecent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <span>Settings</span>
                            </a>
                            <div class="border-t my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm hover:bg-mutedrecent text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    Log out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="rounded-md font-medium bg-white border-2 border-primary text-primary px-4 py-2 hover:bg-primary/10">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="rounded-md text-white font-medium bg-primary hover:bg-primary/90 px-4 py-2">
                        Sign up
                    </a>
                </div>
            @endauth

            <button class="border rounded-md p-2 md:hidden" @click="mobileMenuOpen = !mobileMenuOpen">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="4" x2="20" y1="12" y2="12"></line>
                    <line x1="4" x2="20" y1="6" y2="6"></line>
                    <line x1="4" x2="20" y1="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>

    <!-- mobile menu -->
    <div class="container mx-auto px-4 py-4 md:hidden" x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false">
        <nav class="flex flex-col space-y-4">
            <a href="/feed" class="{{ Route::is('feed') ? 'text-primary' : 'text-foreground' }} flex items-center gap-2 text-sm font-medium transition-colors">
                Feed
            </a>
            <a href="/discover" class="{{ Route::is('discover') ? 'text-primary' : 'text-foreground' }} flex items-center gap-2 text-sm font-medium transition-colors">
                Discover
            </a>
            <a href="/matches" class="{{ Route::is('matches') ? 'text-primary' : 'text-foreground' }} flex items-center gap-2 text-sm font-medium transition-colors">
                Recent Matches
            </a>
            <a href="/messages" class="{{ Route::is('messages') ? 'text-primary' : 'text-foreground' }} flex items-center gap-2 text-sm font-medium transition-colors">
                Inbox
            </a>

            @auth
                <div class="border-t my-2"></div>
                <a href="#" class="flex items-center gap-2 text-sm font-medium hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Profile</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-2 text-sm font-medium hover:text-primary transition-colors text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Log out</span>
                    </button>
                </form>
            @else
                <div class="border-t my-2"></div>
                <a href="{{ route('login') }}" class="flex items-center gap-2 text-sm font-medium hover:text-primary transition-colors">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="flex items-center gap-2 text-sm font-medium hover:text-primary transition-colors">
                    Sign up
                </a>
            @endauth
        </nav>
    </div>
</header>
