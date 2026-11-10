<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E8ECF1] dark:bg-[#0B0B0E] transition-colors" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    },
    quickSwitchOpen: false,
    userMenuOpen: false,
    toastMessage: '',
    showToast(msg) {
        this.toastMessage = msg;
        setTimeout(() => { this.toastMessage = ''; }, 3000);
    }
}" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Workspace') - Office Management System</title>
    
    <!-- Anti-FOUC Dark Mode Detection -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 dark:text-zinc-100 bg-[#E8ECF1] dark:bg-[#0B0B0E] selection:bg-zinc-900 selection:text-white dark:selection:bg-zinc-100 dark:selection:text-zinc-900 relative overflow-x-hidden min-h-screen flex flex-col transition-colors duration-200">

    <!-- Ambient Subtle Soft Diffused Backlight -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[350px] bg-gradient-to-b from-white/60 via-white/20 to-transparent dark:from-zinc-800/30 dark:via-zinc-900/10 pointer-events-none blur-3xl z-0"></div>
    <div class="fixed top-96 left-0 w-[450px] h-[450px] bg-blue-100/30 dark:bg-blue-950/20 pointer-events-none blur-3xl z-0"></div>

    <!-- TOP HEADER -->
    <header class="relative w-full px-4 sm:px-6 lg:px-8 pt-4 pb-2 flex items-center justify-between bg-transparent z-30">
        <!-- Left: Workspace & Division Brand Pill -->
        <div class="flex items-center gap-2.5">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-white/80 hover:bg-white dark:bg-zinc-900/80 dark:hover:bg-zinc-800 backdrop-blur-md border border-white/80 dark:border-zinc-800 shadow-xs transition-colors">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-bold text-slate-900 dark:text-zinc-100 tracking-tight">Office Management</span>
                <span class="text-[11px] text-slate-400 dark:text-zinc-500 font-normal">/</span>
                <span class="text-xs font-medium text-slate-600 dark:text-zinc-400">{{ auth()->user()?->division?->name ?? 'HQ Workspace' }}</span>
            </a>
        </div>

        <!-- Right: Theme Switcher + Notification Bell + User Profile Avatar with Dropdown -->
        <div class="flex items-center space-x-2.5 sm:space-x-3 shrink-0 ml-auto">
            <!-- Dark / Light Mode Toggle Button -->
            <button 
                type="button" 
                @click="toggleDarkMode()" 
                title="Ganti Mode Terang / Gelap" 
                class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/80 hover:bg-white dark:bg-zinc-900/80 dark:hover:bg-zinc-800 backdrop-blur-md border border-white/80 dark:border-zinc-800 shadow-sm flex items-center justify-center text-slate-600 hover:text-slate-900 dark:text-zinc-300 dark:hover:text-yellow-400 transition-colors cursor-pointer"
            >
                <!-- Sun Icon (shown when darkMode is true) -->
                <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <!-- Moon Icon (shown when darkMode is false) -->
                <svg x-show="!darkMode" class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <!-- Notification Bell Icon Button with Dropdown -->
            <div class="relative" x-data="{ notifOpen: false }">
                <button 
                    type="button" 
                    @click="notifOpen = !notifOpen"
                    title="Notifikasi Tugas" 
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/80 hover:bg-white dark:bg-zinc-900/80 dark:hover:bg-zinc-800 backdrop-blur-md border border-white/80 dark:border-zinc-800 shadow-sm flex items-center justify-center text-slate-600 hover:text-slate-900 dark:text-zinc-300 dark:hover:text-zinc-100 relative transition-colors cursor-pointer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if(auth()->user() && auth()->user()->activeTasksCount() > 0)
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 absolute top-2.5 right-2.5 ring-2 ring-white dark:ring-zinc-900"></span>
                    @endif
                </button>

                <!-- Notification Dropdown Panel -->
                <div 
                    x-show="notifOpen" 
                    x-cloak 
                    @click.outside="notifOpen = false"
                    class="absolute right-0 mt-2 w-80 rounded-2xl bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl border border-slate-200/90 dark:border-zinc-800 shadow-xl p-3 z-50 text-xs space-y-2"
                >
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-zinc-800">
                        <h4 class="font-bold text-slate-900 dark:text-zinc-100">Notifikasi Tugas</h4>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300">
                            {{ auth()->user()?->activeTasksCount() ?? 0 }} Tugas
                        </span>
                    </div>

                    @php
                        $activeTask = auth()->user()?->currentActiveTask();
                    @endphp

                    @if($activeTask)
                        <a href="{{ route('tasks.show', $activeTask) }}" class="block p-2.5 rounded-xl bg-blue-50/70 hover:bg-blue-50 dark:bg-blue-950/40 dark:hover:bg-blue-950/60 border border-blue-100 dark:border-blue-900/50 transition-colors">
                            <span class="text-[10px] font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider block mb-0.5">Sedang Dikerjakan</span>
                            <p class="font-bold text-slate-900 dark:text-zinc-100 line-clamp-1">{{ $activeTask->title }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-0.5">Deadline: {{ $activeTask->deadline ? $activeTask->deadline->format('d M Y') : 'Tanpa deadline' }}</p>
                        </a>
                    @else
                        <div class="py-4 text-center text-slate-500 dark:text-zinc-400 text-xs">
                            Tidak ada tugas in-progress saat ini.
                        </div>
                    @endif

                    <div class="pt-1 border-t border-slate-100 dark:border-zinc-800 text-center">
                        <a href="{{ route('tasks.index') }}" class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 hover:underline inline-flex items-center gap-1">
                            <span>Buka Manajemen Tugas</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Profile Avatar Pill with Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    type="button" 
                    @click="open = !open" 
                    title="{{ auth()->user()->name ?? 'Pengguna' }}"
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-full ring-2 ring-white/90 dark:ring-zinc-800 hover:ring-zinc-900 dark:hover:ring-zinc-600 transition-all cursor-pointer overflow-hidden shadow-sm flex items-center justify-center bg-slate-100 dark:bg-zinc-800"
                >
                    <img 
                        src="{{ auth()->user()?->avatar ? auth()->user()->avatarUrl() : 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?auto=format&fit=crop&w=256&h=256&q=80' }}" 
                        alt="Profile" 
                        class="w-full h-full object-cover"
                    >
                </button>

                <!-- Profile Dropdown Menu -->
                <div 
                    x-show="open" 
                    x-cloak 
                    @click.outside="open = false"
                    class="absolute right-0 mt-2 w-64 rounded-2xl bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl border border-slate-200/90 dark:border-zinc-800 shadow-xl p-2 z-50 text-xs space-y-1"
                >
                    <div class="px-3 py-2.5 border-b border-slate-100 dark:border-zinc-800">
                        <p class="font-bold text-slate-900 dark:text-zinc-100 text-sm truncate">{{ auth()->user()->name ?? 'Budi Pratama, M.B.A.' }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-zinc-400 truncate mt-0.5">{{ auth()->user()->email ?? 'direktur@office.com' }}</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700">
                            {{ auth()->user()?->roleDisplayName() ?? 'Direktur' }}
                        </span>
                    </div>

                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-zinc-100 hover:bg-slate-50 dark:hover:bg-zinc-800/70 transition-colors">
                        <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Workspace / Dashboard</span>
                    </a>

                    <a href="{{ route('tasks.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-zinc-100 hover:bg-slate-50 dark:hover:bg-zinc-800/70 transition-colors">
                        <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Manajemen Tugas</span>
                    </a>

                    <a href="{{ route('projects.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-zinc-100 hover:bg-slate-50 dark:hover:bg-zinc-800/70 transition-colors">
                        <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        <span>Manajemen Project</span>
                    </a>

                    <a href="{{ route('employees.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-zinc-100 hover:bg-slate-50 dark:hover:bg-zinc-800/70 transition-colors">
                        <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Direktori Tim & Karyawan</span>
                    </a>

                    <a href="{{ route('organization.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-zinc-100 hover:bg-slate-50 dark:hover:bg-zinc-800/70 transition-colors">
                        <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Struktur Organisasi</span>
                    </a>

                    <div class="border-t border-slate-100 dark:border-zinc-800 pt-1">
                        <button 
                            type="button" 
                            @click="quickSwitchOpen = true; open = false" 
                            class="w-full flex items-center space-x-2.5 px-3 py-2 rounded-xl text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition-colors text-left font-medium cursor-pointer"
                        >
                            <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span>Beralih Peran (Quick Switcher)</span>
                        </button>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-2.5 px-3 py-2 rounded-xl text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors text-left font-medium cursor-pointer">
                                <svg class="w-4 h-4 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Outer Wrapper: Left Floating Dock + Content Area -->
    <div class="flex-1 flex w-full relative">
        
        <!-- 2. LEFT FLOATING VERTICAL DOCK (Vertically centered, Frosted Glass look) -->
        <aside class="fixed left-4 sm:left-6 top-1/2 -translate-y-1/2 z-30 hidden md:flex flex-col items-center bg-white/75 dark:bg-zinc-900/85 backdrop-blur-xl border border-white/80 dark:border-zinc-800/80 rounded-full p-2 py-4 shadow-xl shadow-slate-300/40 dark:shadow-black/50 gap-3 transition-colors">
            <!-- Icon 1: Grid / Workspace -->
            <a 
                href="{{ route('dashboard') }}" 
                title="Workspace" 
                class="w-10 h-10 rounded-full {{ request()->routeIs('dashboard*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-md' : 'text-slate-400 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-white/80 dark:hover:bg-zinc-800' }} flex items-center justify-center transition-all cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </a>

            <!-- Icon 2: Tasks -->
            <a 
                href="{{ route('tasks.index') }}" 
                title="Manajemen Tugas" 
                class="w-10 h-10 rounded-full {{ request()->routeIs('tasks.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-md' : 'text-slate-400 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-white/80 dark:hover:bg-zinc-800' }} flex items-center justify-center transition-all cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </a>

            <!-- Icon 3: Projects -->
            <a 
                href="{{ route('projects.index') }}" 
                title="Project & Tim" 
                class="w-10 h-10 rounded-full {{ request()->routeIs('projects.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-md' : 'text-slate-400 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-white/80 dark:hover:bg-zinc-800' }} flex items-center justify-center transition-all cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </a>

            <!-- Icon 4: Team / People (Directory) -->
            <a 
                href="{{ route('employees.index') }}" 
                title="Data Karyawan & Tim" 
                class="w-10 h-10 rounded-full {{ request()->routeIs('employees.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-md' : 'text-slate-400 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-white/80 dark:hover:bg-zinc-800' }} flex items-center justify-center transition-all cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </a>

            <!-- Icon 5: Organization Hierarchy -->
            <a 
                href="{{ route('organization.index') }}" 
                title="Struktur Organisasi" 
                class="w-10 h-10 rounded-full {{ request()->routeIs('organization.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-md' : 'text-slate-400 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-white/80 dark:hover:bg-zinc-800' }} flex items-center justify-center transition-all cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </a>
        </aside>

        <!-- Main Body Canvas -->
        <main class="flex-1 w-full pl-4 md:pl-24 pr-4 sm:pr-8 lg:pr-10 py-6 min-w-0">
            <!-- Toast notification pill -->
            <div x-show="toastMessage" x-cloak class="fixed top-20 right-8 z-50 bg-white/95 dark:bg-zinc-900/95 text-slate-900 dark:text-zinc-100 border border-slate-200/90 dark:border-zinc-800 px-4 py-2.5 rounded-full shadow-xl flex items-center gap-2 text-xs font-semibold animate-bounce">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span x-text="toastMessage"></span>
            </div>

            @yield('content')
        </main>
    </div>

    <!-- Quick Role Switcher Modal -->
    <div x-show="quickSwitchOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="quickSwitchOpen = false" class="fixed inset-0 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs"></div>

        <div class="relative bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 dark:border-zinc-800 z-10 overflow-hidden text-slate-900 dark:text-zinc-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-zinc-800">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-zinc-100">Ganti Akun Demo (Testing Peran)</h3>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Uji coba otorisasi & tampilan peran dalam sistem</p>
                </div>
                <button @click="quickSwitchOpen = false" class="text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-200 p-1 text-lg font-bold cursor-pointer">&times;</button>
            </div>

            <div class="mt-4 space-y-2.5 max-h-96 overflow-y-auto pr-1">
                @php
                    $demoAccounts = [
                        ['email' => 'direktur@office.com', 'role' => 'Direktur', 'name' => 'Budi Pratama, M.B.A.', 'desc' => 'Akses penuh seluruh perusahaan & semua level'],
                        ['email' => 'vp.tech@office.com', 'role' => 'VP', 'name' => 'Ir. Hendra Gunawan', 'desc' => 'Divisi Teknologi (Manager, Lead, Staff TECH)'],
                        ['email' => 'manager.it@office.com', 'role' => 'Manager', 'name' => 'Reza Rahadian, S.Kom', 'desc' => 'Kelola Team Lead & Staff IT'],
                        ['email' => 'lead.dev@office.com', 'role' => 'Team Lead', 'name' => 'Fauzil Adzim', 'desc' => 'Bisa buat & assign tugas ke Staff dev'],
                        ['email' => 'staff.dev1@office.com', 'role' => 'Staff', 'name' => 'Ahmad Fikri', 'desc' => 'Hanya lihat & update tugas miliknya'],
                    ];
                @endphp

                @foreach($demoAccounts as $demo)
                    <form action="{{ route('quick-login') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $demo['email'] }}">
                        <button type="submit" class="w-full text-left p-3.5 rounded-2xl border border-slate-200 dark:border-zinc-800 bg-slate-50/80 dark:bg-zinc-800/50 hover:border-slate-300 dark:hover:border-zinc-700 hover:bg-white dark:hover:bg-zinc-800 transition-all duration-150 flex items-center justify-between group cursor-pointer shadow-xs">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-xs text-slate-900 dark:text-zinc-100 group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $demo['name'] }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-300">{{ $demo['role'] }}</span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1">{{ $demo['desc'] }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

</body>
</html>



    <!-- Quick Role Switcher Modal -->
    <div x-show="quickSwitchOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="quickSwitchOpen = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>

        <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 z-10 overflow-hidden text-slate-900">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Ganti Akun Demo (Testing Peran)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Uji coba otorisasi & tampilan peran dalam sistem</p>
                </div>
                <button @click="quickSwitchOpen = false" class="text-slate-400 hover:text-slate-700 p-1 text-lg font-bold cursor-pointer">&times;</button>
            </div>

            <div class="mt-4 space-y-2.5 max-h-96 overflow-y-auto pr-1">
                @php
                    $demoAccounts = [
                        ['email' => 'direktur@office.com', 'role' => 'Direktur', 'name' => 'Budi Pratama, M.B.A.', 'desc' => 'Akses penuh seluruh perusahaan & semua level'],
                        ['email' => 'vp.tech@office.com', 'role' => 'VP', 'name' => 'Ir. Hendra Gunawan', 'desc' => 'Divisi Teknologi (Manager, Lead, Staff TECH)'],
                        ['email' => 'manager.it@office.com', 'role' => 'Manager', 'name' => 'Reza Rahadian, S.Kom', 'desc' => 'Kelola Team Lead & Staff IT'],
                        ['email' => 'lead.dev@office.com', 'role' => 'Team Lead', 'name' => 'Fauzil Adzim', 'desc' => 'Bisa buat & assign tugas ke Staff dev'],
                        ['email' => 'staff.dev1@office.com', 'role' => 'Staff', 'name' => 'Ahmad Fikri', 'desc' => 'Hanya lihat & update tugas miliknya'],
                    ];
                @endphp

                @foreach($demoAccounts as $demo)
                    <form action="{{ route('quick-login') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $demo['email'] }}">
                        <button type="submit" class="w-full text-left p-3.5 rounded-2xl border border-slate-200 bg-slate-50/80 hover:border-slate-300 hover:bg-white transition-all duration-150 flex items-center justify-between group cursor-pointer shadow-xs">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-xs text-slate-900 group-hover:text-blue-600">{{ $demo['name'] }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">{{ $demo['role'] }}</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">{{ $demo['desc'] }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

</body>
</html>
