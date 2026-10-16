<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E8ECF1]" x-data="{
    darkMode: false,
    quickSwitchOpen: false,
    callActive: true,
    micMuted: false,
    camDisabled: false,
    speakerMuted: false,
    scheduleDrawerOpen: false,
    notificationOpen: false,
    userMenuOpen: false,
    toastMessage: '',
    showToast(msg) {
        this.toastMessage = msg;
        setTimeout(() => { this.toastMessage = ''; }, 3000);
    }
}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Workspace') - Office Management System</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-[#E8ECF1] selection:bg-zinc-900 selection:text-white relative overflow-x-hidden min-h-screen flex flex-col">

    <!-- Ambient Subtle Soft Diffused Backlight -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[350px] bg-gradient-to-b from-white/60 via-white/20 to-transparent pointer-events-none blur-3xl z-0"></div>
    <div class="fixed top-96 left-0 w-[450px] h-[450px] bg-blue-100/30 pointer-events-none blur-3xl z-0"></div>

    <!-- 1. DYNAMIC ISLAND STYLE FLOATING CAPSULE (Dominan Hitam-Putih dengan semantic green, yellow, red) -->
    <div class="fixed top-3.5 left-1/2 -translate-x-1/2 z-50 hidden md:flex items-center bg-[#101014]/95 backdrop-blur-2xl text-white rounded-full py-1.5 pl-4 pr-2 shadow-[0_16px_40px_rgba(0,0,0,0.30)] border border-white/[0.12] max-w-2xl transition-all duration-300 pointer-events-auto">
        <!-- Live Status Green Pulse (Hijau: Berhasil / Sesi Berjalan) -->
        <span class="flex h-2.5 w-2.5 relative mr-2.5 shrink-0">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
        </span>

        <span class="text-xs sm:text-sm font-bold tracking-tight text-white whitespace-nowrap mr-3.5">
            Your Schedule
        </span>

        <!-- Date Pill: Hitam-Putih Minimalist -->
        <button 
            type="button" 
            @click="scheduleDrawerOpen = !scheduleDrawerOpen"
            class="bg-white/10 hover:bg-white/15 text-zinc-200 rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border border-white/10 transition-colors cursor-pointer mr-3 shadow-xs"
        >
            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>28 March</span>
        </button>

        <!-- Monochrome Timeline Progress Track with Semantic Indicator Lights -->
        <div class="bg-white/[0.08] text-white border border-white/[0.08] rounded-full py-1 px-3 flex items-center gap-2.5 relative">
            <!-- Hijau Chip (Sedang Sesi Aktif) + X dismiss (Merah saat hover/alert) -->
            <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-300 bg-emerald-500/15 border border-emerald-500/30 px-2 py-0.5 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
                <span>30 min</span>
                <button type="button" @click="showToast('Sesi 30 menit ditutup')" class="hover:text-rose-400 text-zinc-400 cursor-pointer ml-0.5" title="Tutup Sesi (Danger/Close)">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- 2:00 PM Slot -->
            <span class="text-[11px] font-bold text-zinc-200">2:00 PM</span>

            <!-- Overlapping Avatars (2:00 PM) -->
            <div class="flex -space-x-1.5 items-center">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&h=100&q=80" alt="Attendant" class="w-5 h-5 rounded-full object-cover ring-1 ring-white/40">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&h=100&q=80" alt="Attendant" class="w-5 h-5 rounded-full object-cover ring-1 ring-white/40">
            </div>

            <!-- Current Indicator Marker: 2:30 PM (Kuning / Hati-hati upcoming reminder) -->
            <div class="relative flex flex-col items-center">
                <span class="bg-amber-400 text-zinc-950 text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow -translate-y-2 whitespace-nowrap leading-tight">
                    2:30 PM
                </span>
                <div class="w-0.5 h-3 bg-amber-400 absolute -bottom-1"></div>
            </div>

            <!-- Blue Video Camera Badge (Zoom meeting at 3:00 PM) with Red urgent dot if overdue/alert -->
            <div class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-xs relative">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                </svg>
                <!-- Merah Alert Indicator (Merah untuk urgent/danger) -->
                <span class="w-2 h-2 rounded-full bg-rose-500 absolute -top-0.5 -right-0.5 ring-1 ring-zinc-900 animate-ping"></span>
            </div>

            <!-- 3:00 PM Slot -->
            <span class="text-[11px] font-bold text-zinc-200">3:00 PM</span>

            <!-- Overlapping Avatars (3:00 PM) -->
            <div class="flex -space-x-1.5 items-center">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&h=100&q=80" alt="Attendant" class="w-5 h-5 rounded-full object-cover ring-1 ring-white/40">
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=100&h=100&q=80" alt="Attendant" class="w-5 h-5 rounded-full object-cover ring-1 ring-white/40">
            </div>

            <!-- Diagonal Arrow Button (White/Black) -->
            <button 
                type="button" 
                @click="scheduleDrawerOpen = !scheduleDrawerOpen" 
                title="Buka Kalender Jadwal"
                class="w-7 h-7 rounded-full bg-white hover:bg-zinc-200 text-zinc-950 flex items-center justify-center shadow transition-transform hover:scale-110 cursor-pointer ml-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                </svg>
            </button>
        </div>
    </div>

    <!-- 2. TOP HEADER (Menyatu dengan background, tanpa blur/border, ikut ter-scroll ke atas) -->
    <header class="relative w-full px-4 sm:px-6 lg:px-8 pt-4 pb-2 flex items-center justify-between bg-transparent z-30">
        <!-- Left Spacer -->
        <div class="hidden sm:block w-11 shrink-0"></div>

        <!-- Right: Notification Bell with Red Dot + User Profile Avatar with Dropdown -->
        <div class="flex items-center space-x-3 shrink-0 ml-auto">
            <!-- Notification Bell Icon Button (Glass look) -->
            <button 
                type="button" 
                @click="notificationOpen = !notificationOpen"
                title="Notifikasi" 
                class="w-11 h-11 rounded-full bg-white/80 hover:bg-white backdrop-blur-md border border-white/80 shadow-sm flex items-center justify-center text-slate-600 hover:text-slate-900 relative transition-colors cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <!-- Active dot (Merah untuk unread notification) -->
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 absolute top-2.5 right-2.5 ring-2 ring-white animate-pulse"></span>
            </button>

            <!-- User Profile Avatar Pill with Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    type="button" 
                    @click="open = !open" 
                    title="{{ auth()->user()->name ?? 'Pengguna' }}"
                    class="w-11 h-11 rounded-full ring-2 ring-white/90 hover:ring-zinc-900 transition-all cursor-pointer overflow-hidden shadow-sm flex items-center justify-center bg-slate-100"
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
                    class="absolute right-0 mt-2 w-64 rounded-2xl bg-white/95 backdrop-blur-xl border border-slate-200/90 shadow-xl p-2 z-50 text-xs space-y-1"
                >
                    <div class="px-3 py-2.5 border-b border-slate-100">
                        <p class="font-bold text-slate-900 text-sm truncate">{{ auth()->user()->name ?? 'Budi Pratama, M.B.A.' }}</p>
                        <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ auth()->user()->email ?? 'direktur@office.com' }}</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 text-zinc-800 border border-zinc-200">
                            {{ auth()->user()?->roleDisplayName() ?? 'Direktur' }}
                        </span>
                    </div>

                    <a href="{{ route('projects.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        <span>Manajemen Project</span>
                    </a>

                    <a href="{{ route('employees.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Direktori Tim & Karyawan</span>
                    </a>

                    <button 
                        type="button" 
                        @click="quickSwitchOpen = true; open = false" 
                        class="w-full flex items-center space-x-2.5 px-3 py-2 rounded-xl text-indigo-600 hover:bg-indigo-50 transition-colors text-left font-medium cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Beralih Peran (Quick Switcher)</span>
                    </button>

                    <div class="border-t border-slate-100 pt-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-2.5 px-3 py-2 rounded-xl text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-colors text-left font-medium cursor-pointer">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <aside class="fixed left-4 sm:left-6 top-1/2 -translate-y-1/2 z-30 hidden md:flex flex-col items-center bg-white/75 backdrop-blur-xl border border-white/80 rounded-full p-2 py-4 shadow-xl shadow-slate-300/40 gap-3">
            <!-- Icon 1: Grid / Workspace -->
            <a 
                href="{{ route('dashboard') }}" 
                title="Workspace" 
                class="w-10 h-10 rounded-full {{ request()->routeIs('dashboard') ? 'bg-zinc-900 text-white shadow-md' : 'text-slate-400 hover:text-zinc-900 hover:bg-white/80' }} flex items-center justify-center transition-all cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </a>

            <!-- Icon 2: Projects (New Project Menu) -->
            <a 
                href="{{ route('projects.index') }}" 
                title="Project & Tim" 
                class="w-10 h-10 rounded-full {{ request()->routeIs('projects.*') ? 'bg-zinc-900 text-white shadow-md' : 'text-slate-400 hover:text-zinc-900 hover:bg-white/80' }} flex items-center justify-center transition-all cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </a>

            <!-- Icon 3: Team / People (Directory) -->
            <a 
                href="{{ route('employees.index') }}" 
                title="Data Karyawan & Tim" 
                class="w-10 h-10 rounded-full {{ request()->routeIs('employees.*') ? 'bg-zinc-900 text-white shadow-md' : 'text-slate-400 hover:text-zinc-900 hover:bg-white/80' }} flex items-center justify-center transition-all cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </a>

            <!-- Icon 3: Chat / Messages -->
            <button 
                type="button" 
                @click="showToast('Fitur pesan instan siap digunakan')"
                title="Pesan & Diskusi" 
                class="w-10 h-10 rounded-full text-slate-400 hover:text-zinc-900 hover:bg-white/80 flex items-center justify-center transition-all cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </button>

            <!-- Icon 4: Calendar / Schedule -->
            <button 
                type="button" 
                @click="scheduleDrawerOpen = true"
                title="Kalender Jadwal" 
                class="w-10 h-10 rounded-full text-slate-400 hover:text-zinc-900 hover:bg-white/80 flex items-center justify-center transition-all cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </button>
        </aside>

        <!-- Main Body Canvas -->
        <main class="flex-1 w-full pl-4 md:pl-24 pr-4 sm:pr-8 lg:pr-10 py-6 min-w-0">
            <!-- Toast notification pill -->
            <div x-show="toastMessage" x-cloak class="fixed top-20 right-8 z-50 bg-white/95 backdrop-blur-xl text-slate-900 border border-slate-200/90 px-4 py-2.5 rounded-full shadow-xl flex items-center gap-2 text-xs font-semibold animate-bounce">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span x-text="toastMessage"></span>
            </div>

            @yield('content')
        </main>
    </div>

    <!-- 3. FLOATING VIDEO CALL CONTROL BAR (Bottom Right - Glass look) -->
    <div x-show="callActive" x-cloak class="fixed bottom-6 right-6 z-40 bg-white/85 backdrop-blur-xl border border-white/80 rounded-full p-2 px-3 flex items-center gap-2.5 shadow-xl shadow-slate-300/40">
        <!-- Active Meeting Camera Icon with green pulse -->
        <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md relative shrink-0">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
            </svg>
            <span class="w-2 h-2 rounded-full bg-emerald-400 absolute top-0.5 right-0.5 animate-ping"></span>
        </div>

        <!-- Video Camera Toggle Button -->
        <button 
            type="button" 
            @click="camDisabled = !camDisabled; showToast(camDisabled ? 'Kamera dimatikan' : 'Kamera diaktifkan')"
            :class="camDisabled ? 'bg-rose-100 text-rose-700 border border-rose-300' : 'bg-slate-100/90 text-slate-700 hover:bg-slate-200'"
            class="w-9 h-9 rounded-full flex items-center justify-center transition-colors cursor-pointer"
            title="Toggle Kamera"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
        </button>

        <!-- Mic Toggle Button -->
        <button 
            type="button" 
            @click="micMuted = !micMuted; showToast(micMuted ? 'Mikrofon dibisukan' : 'Mikrofon aktif')"
            :class="micMuted ? 'bg-rose-100 text-rose-700 border border-rose-300' : 'bg-slate-100/90 text-slate-700 hover:bg-slate-200'"
            class="w-9 h-9 rounded-full flex items-center justify-center transition-colors cursor-pointer"
            title="Toggle Mikrofon"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
            </svg>
        </button>

        <!-- Speaker Toggle Button -->
        <button 
            type="button" 
            @click="speakerMuted = !speakerMuted; showToast(speakerMuted ? 'Speaker dibisukan' : 'Speaker aktif')"
            :class="speakerMuted ? 'bg-rose-100 text-rose-700 border border-rose-300' : 'bg-slate-100/90 text-slate-700 hover:bg-slate-200'"
            class="w-9 h-9 rounded-full flex items-center justify-center transition-colors cursor-pointer"
            title="Toggle Audio"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
            </svg>
        </button>

        <!-- Red End Call Button (Merah: Danger / End) -->
        <button 
            type="button" 
            @click="callActive = false; showToast('Panggilan Zoom Meeting diakhiri')"
            class="w-9 h-9 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center shadow-md active:scale-95 transition-all cursor-pointer"
            title="Akhiri Panggilan"
        >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" class="rotate-[135deg] origin-center" />
            </svg>
        </button>
    </div>

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

    <!-- Schedule Drawer (When clicking 28 March or Schedule) -->
    <div x-show="scheduleDrawerOpen" x-cloak class="fixed inset-0 z-50 flex justify-end">
        <div @click="scheduleDrawerOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"></div>
        <div class="relative w-full max-w-md bg-white/95 backdrop-blur-xl h-full shadow-2xl border-l border-slate-200 p-6 flex flex-col z-10 overflow-y-auto text-slate-900">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Jadwal Agenda 28 March</h3>
                    <p class="text-xs text-slate-500">Kalender pertemuan & agenda kerja hari ini</p>
                </div>
                <button @click="scheduleDrawerOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <div class="mt-6 space-y-4">
                <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200">
                    <span class="text-[11px] font-bold text-emerald-700 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        2:00 PM - 2:30 PM (Sedang Berlangsung)
                    </span>
                    <h4 class="text-sm font-bold text-slate-900 mt-1">Review Penawaran Klien Goldman-Sachs</h4>
                    <p class="text-xs text-slate-600 mt-0.5">Bersama John Doe & Tim Marketing</p>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200">
                    <span class="text-[11px] font-bold text-amber-700">2:30 PM - 3:00 PM (Berikutnya)</span>
                    <h4 class="text-sm font-bold text-slate-900 mt-1">Zoom Meeting: Fed-Ex Onboarding</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Bersama Rebecca Morris</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200">
                    <span class="text-[11px] font-bold text-slate-600">3:30 PM - 4:15 PM</span>
                    <h4 class="text-sm font-bold text-slate-900 mt-1">Diskusi Teknis Arsitektur Cloud</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Bersama Ir. Hendra Gunawan (VP Tech)</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
