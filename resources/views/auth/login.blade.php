<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" x-init="if (darkMode) document.documentElement.classList.add('dark')">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Office Management System</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased text-[#18181B] dark:text-[#F4F4F5] bg-[#F3F4F6] dark:bg-[#09090B] flex items-center justify-center p-4 sm:p-6 lg:p-8 relative selection:bg-zinc-900 selection:text-white transition-colors duration-200">
    
    <!-- Top Right Theme Switcher -->
    <div class="fixed top-4 right-4 sm:top-6 sm:right-6 z-30">
        <button @click="toggleTheme()" type="button" aria-label="Toggle Theme" class="p-2.5 rounded-xl border border-[#E4E4E7] dark:border-[#27272A] bg-white/90 dark:bg-[#121215]/90 backdrop-blur-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white shadow-sm transition-all duration-150 hover:scale-105 active:scale-95">
            <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg x-show="darkMode" x-cloak class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>
    </div>

    <!-- Main Outer Container: rounded-3xl, shadow-xl, max-w ~1040px, centered -->
    <div class="w-full max-w-[1040px] bg-white dark:bg-[#111115] rounded-3xl shadow-xl shadow-zinc-200/50 dark:shadow-none border border-[#E4E4E7]/80 dark:border-[#27272A] p-4 sm:p-6 lg:p-7 relative overflow-hidden" x-data="{
        email: '{{ old('email', '') }}',
        password: '',
        showDemo: false,
        fillAccount(email) {
            this.email = email;
            this.password = 'password';
        }
    }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">
            
            <!-- KOLOM KIRI: Form Login (~45% lebar desktop, full width di mobile) -->
            <div class="lg:col-span-5 flex flex-col justify-between py-2 sm:py-3 px-1 sm:px-3">
                <div>
                    <!-- Heading Form Login -->
                    <div class="mb-5 sm:mb-6">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                            Masuk ke Dashboard
                        </h1>
                        <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1.5">
                            Isi form di bawah untuk masuk
                        </p>
                    </div>

                    <!-- Flash Messages & Error Alerts -->
                    @if(session('info'))
                        <div class="mb-4 p-3 rounded-xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 shrink-0 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('info') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200/60 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3 rounded-xl bg-[#FEF2F2] dark:bg-red-950/40 border border-[#FECACA] dark:border-red-900/50 text-[#DC2626] dark:text-red-300 text-xs font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <!-- Standard Form -->
                    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="email" class="block text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">
                                Alamat Email
                            </label>
                            <input id="email" name="email" type="email" autocomplete="email" required x-model="email" placeholder="nama@office.com" class="w-full px-3.5 py-2.5 rounded-xl bg-[#FAFAFA] dark:bg-[#0A0A0C] border border-[#E4E4E7] dark:border-[#27272A] text-xs sm:text-sm text-[#18181B] dark:text-[#F4F4F5] placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black dark:focus:ring-white/10 dark:focus:border-white transition-colors">
                        </div>

                        <div>
                            <label for="password" class="block text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">
                                Kata Sandi
                            </label>
                            <input id="password" name="password" type="password" autocomplete="current-password" required x-model="password" placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl bg-[#FAFAFA] dark:bg-[#0A0A0C] border border-[#E4E4E7] dark:border-[#27272A] text-xs sm:text-sm text-[#18181B] dark:text-[#F4F4F5] placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black dark:focus:ring-white/10 dark:focus:border-white transition-colors">
                        </div>

                        <!-- 'Ingat saya' kiri, 'Lupa password?' kanan (tidak ada text ungu) -->
                        <div class="flex items-center justify-between text-xs pt-0.5">
                            <label class="flex items-center text-zinc-500 dark:text-zinc-400 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded bg-zinc-100 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-black dark:text-white focus:ring-black dark:focus:ring-white focus:ring-offset-0">
                                <span class="ml-2 text-xs">Ingat saya</span>
                            </label>
                            <a href="javascript:void(0)" @click="alert('Silakan hubungi IT Administrator atau gunakan Akun Demo Cepat di bawah.')" class="text-xs text-zinc-600 dark:text-zinc-400 hover:text-black dark:hover:text-white hover:underline font-medium">
                                Lupa password?
                            </a>
                        </div>

                        <!-- Tombol submit dengan aksen merah maroon & red -->
                        <button type="submit" class="w-full py-2.5 sm:py-3 px-4 rounded-xl text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-[#DC2626] to-[#881337] hover:from-[#EF4444] hover:to-[#991B1B] shadow-lg shadow-rose-950/20 active:scale-[0.99] transition-all duration-150 flex items-center justify-center space-x-2 cursor-pointer">
                            <span>Masuk ke Dashboard</span>
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Section Login Cepat Mode Demo: Collapsible Accordion (tanpa text ungu) -->
                <div class="mt-6 pt-4 border-t border-[#E4E4E7] dark:border-[#27272A]">
                    <!-- Accordion Trigger Button -->
                    <button type="button" @click="showDemo = !showDemo" class="w-full py-2 px-3 rounded-xl border border-dashed border-zinc-200 dark:border-zinc-800 hover:border-zinc-400 dark:hover:border-zinc-600 bg-zinc-50/70 dark:bg-zinc-900/40 flex items-center justify-between text-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 transition-colors">
                        <span class="flex items-center space-x-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="font-medium text-[11px] sm:text-xs text-zinc-700 dark:text-zinc-300">Login Cepat Mode Demo</span>
                        </span>
                        <span class="flex items-center space-x-1 text-[11px] text-zinc-700 dark:text-zinc-300 font-semibold">
                            <span x-text="showDemo ? 'Tutup akun demo' : 'Lihat akun demo'">Lihat akun demo</span>
                            <svg class="w-3.5 h-3.5 transform transition-transform duration-200" :class="showDemo ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>

                    <!-- Accordion Content -->
                    <div x-show="showDemo" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-2.5 space-y-1.5">
                        <div class="flex items-center justify-between text-[10px] text-zinc-400 px-1 pb-0.5">
                            <span>Klik nama untuk isi form / Masuk 1-klik</span>
                            <span>Password: <code class="font-mono text-zinc-700 dark:text-zinc-300 font-bold">password</code></span>
                        </div>

                        @php
                            $rolesList = [
                                [
                                    'email' => 'direktur@office.com',
                                    'role' => 'Direktur',
                                    'name' => 'Budi Pratama, M.B.A.',
                                    'badge' => 'Level 1 &bull; Full Access',
                                    'color' => 'bg-amber-50 text-amber-800 border-amber-200/70 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-900/50'
                                ],
                                [
                                    'email' => 'vp.tech@office.com',
                                    'role' => 'VP',
                                    'name' => 'Ir. Hendra Gunawan',
                                    'badge' => 'Level 2 &bull; Divisi Tech',
                                    'color' => 'bg-slate-100 text-slate-800 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700'
                                ],
                                [
                                    'email' => 'manager.it@office.com',
                                    'role' => 'Manager',
                                    'name' => 'Reza Rahadian, S.Kom.',
                                    'badge' => 'Level 3 &bull; Kelola Tim',
                                    'color' => 'bg-cyan-50 text-cyan-800 border-cyan-200/70 dark:bg-cyan-950/40 dark:text-cyan-300 dark:border-cyan-900/50'
                                ],
                                [
                                    'email' => 'lead.dev@office.com',
                                    'role' => 'Team Lead',
                                    'name' => 'Fauzil Adzim, S.Tr.Kom.',
                                    'badge' => 'Level 4 &bull; Assign Staff',
                                    'color' => 'bg-emerald-50 text-emerald-800 border-emerald-200/70 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-900/50'
                                ],
                                [
                                    'email' => 'staff.dev1@office.com',
                                    'role' => 'Staff',
                                    'name' => 'Ahmad Fikri',
                                    'badge' => 'Level 5 &bull; Tugas Sendiri',
                                    'color' => 'bg-zinc-100 text-zinc-800 border-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700'
                                ],
                            ];
                        @endphp

                        @foreach($rolesList as $r)
                            <div class="p-2 rounded-xl border border-[#E4E4E7] dark:border-[#27272A] hover:border-zinc-400 dark:hover:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-[#18181D] transition-all duration-150 flex items-center justify-between gap-1.5">
                                <button type="button" @click="fillAccount('{{ $r['email'] }}')" class="flex items-center space-x-2 min-w-0 text-left flex-1 group">
                                    <span class="w-6 h-6 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center text-[10px] font-bold group-hover:bg-black group-hover:text-white dark:group-hover:bg-white dark:group-hover:text-black transition-colors shrink-0">
                                        {{ substr($r['role'], 0, 1) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-[#18181B] dark:text-[#F4F4F5] group-hover:text-black dark:group-hover:text-white truncate leading-tight">
                                            {{ $r['name'] }}
                                        </p>
                                        <p class="text-[10px] font-mono text-zinc-400 truncate">
                                            {{ $r['email'] }}
                                        </p>
                                    </div>
                                </button>

                                <div class="flex items-center space-x-1.5 shrink-0">
                                    <span class="hidden sm:inline-block text-[9px] font-medium px-1.5 py-0.5 rounded-md border {{ $r['color'] }}">
                                        {!! $r['badge'] !!}
                                    </span>
                                    <a href="{{ route('quick-login', ['email' => $r['email']]) }}" class="px-2 py-0.5 text-[10px] font-semibold rounded-lg bg-zinc-100 hover:bg-black text-zinc-800 hover:text-white dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-white dark:hover:text-black border border-zinc-200 dark:border-zinc-700 transition-colors shadow-xs">
                                        Masuk &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: Preview Panel (~55% lebar desktop, disembunyikan di mobile) -->
            <div class="hidden lg:flex lg:col-span-7 flex-col">
                <!-- Nested Card: background netral soft, full height -->
                <div class="h-full bg-gradient-to-br from-zinc-50/90 via-zinc-100/60 to-slate-100/80 dark:from-zinc-900/60 dark:via-[#121216] dark:to-zinc-900/40 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 p-6 lg:p-7 flex flex-col justify-between relative overflow-hidden select-none">
                    
                    <!-- Decorative subtle gradient glow blobs (warna hijau & kuning lembut) -->
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-emerald-400/10 dark:bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-amber-400/10 dark:bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- Bagian Atas Panel Preview (tanpa text ungu) -->
                    <div class="relative z-10">
                        <!-- 1. Badge kecil uppercase tracking-wide -->
                        <div class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white dark:bg-zinc-800/90 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 shadow-xs mb-3.5">
                            Office Management System
                        </div>

                        <!-- 2. Heading pendek 2 baris -->
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900 dark:text-white leading-snug max-w-sm">
                            Kelola & pantau pekerjaan tim Anda secara profesional
                        </h2>
                    </div>

                    <!-- Style khusus untuk animasi pulse radar hub tengah warna hijau -->
                    <style>
                        @keyframes radarPulseGreen {
                            0% {
                                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5);
                                transform: scale(1);
                            }
                            50% {
                                box-shadow: 0 0 0 12px rgba(16, 185, 129, 0);
                                transform: scale(1.05);
                            }
                            100% {
                                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
                                transform: scale(1);
                            }
                        }
                        .animate-radar-hub-green {
                            animation: radarPulseGreen 2.2s infinite ease-in-out;
                        }
                    </style>

                    <!-- 3. Animated Flow Diagram (Sumber Tugas -> Hub -> Output) dengan Line Merah (#DC2626) -->
                    <div class="relative w-full my-auto py-2">
                        <!-- SVG Curved Paths & Animated Flowing Dots Warna Merah (#DC2626) -->
                        <svg class="absolute inset-0 w-full h-full pointer-events-none z-0" viewBox="0 0 440 180" preserveAspectRatio="none">
                            <!-- Left 1 (Direktur) ke Hub Tengah -->
                            <path d="M 120,30 C 165,30 165,90 196,90" fill="none" stroke="#DC2626" stroke-width="1.75" stroke-dasharray="3 3" opacity="0.85"/>
                            <circle r="3.5" fill="#DC2626">
                                <animateMotion dur="2.4s" repeatCount="indefinite" path="M 120,30 C 165,30 165,90 196,90" />
                            </circle>

                            <!-- Left 2 (Manager) ke Hub Tengah -->
                            <path d="M 120,90 L 196,90" fill="none" stroke="#DC2626" stroke-width="1.75" stroke-dasharray="3 3" opacity="0.85"/>
                            <circle r="3.5" fill="#DC2626">
                                <animateMotion dur="2.0s" begin="0.4s" repeatCount="indefinite" path="M 120,90 L 196,90" />
                            </circle>

                            <!-- Left 3 (Team Lead) ke Hub Tengah -->
                            <path d="M 120,150 C 165,150 165,90 196,90" fill="none" stroke="#DC2626" stroke-width="1.75" stroke-dasharray="3 3" opacity="0.85"/>
                            <circle r="3.5" fill="#DC2626">
                                <animateMotion dur="2.6s" begin="0.8s" repeatCount="indefinite" path="M 120,150 C 165,150 165,90 196,90" />
                            </circle>

                            <!-- Hub Tengah ke Right 1 (Notifikasi) -->
                            <path d="M 244,90 C 275,90 275,24 320,24" fill="none" stroke="#DC2626" stroke-width="1.75" stroke-dasharray="3 3" opacity="0.85"/>
                            <circle r="3.5" fill="#DC2626">
                                <animateMotion dur="2.3s" begin="0.2s" repeatCount="indefinite" path="M 244,90 C 275,90 275,24 320,24" />
                            </circle>

                            <!-- Hub Tengah ke Right 2 (Update Dashboard) -->
                            <path d="M 244,90 C 275,90 275,68 320,68" fill="none" stroke="#DC2626" stroke-width="1.75" stroke-dasharray="3 3" opacity="0.85"/>
                            <circle r="3.5" fill="#DC2626">
                                <animateMotion dur="2.5s" begin="0.6s" repeatCount="indefinite" path="M 244,90 C 275,90 275,68 320,68" />
                            </circle>

                            <!-- Hub Tengah ke Right 3 (Progress Tracking) -->
                            <path d="M 244,90 C 275,90 275,112 320,112" fill="none" stroke="#DC2626" stroke-width="1.75" stroke-dasharray="3 3" opacity="0.85"/>
                            <circle r="3.5" fill="#DC2626">
                                <animateMotion dur="2.2s" begin="1.0s" repeatCount="indefinite" path="M 244,90 C 275,90 275,112 320,112" />
                            </circle>

                            <!-- Hub Tengah ke Right 4 (History Log) -->
                            <path d="M 244,90 C 275,90 275,156 320,156" fill="none" stroke="#DC2626" stroke-width="1.75" stroke-dasharray="3 3" opacity="0.85"/>
                            <circle r="3.5" fill="#DC2626">
                                <animateMotion dur="2.7s" begin="0.5s" repeatCount="indefinite" path="M 244,90 C 275,90 275,156 320,156" />
                            </circle>
                        </svg>

                        <!-- HTML Node Elements Overlay -->
                        <div class="grid grid-cols-12 items-center gap-2 relative z-10">
                            <!-- 1. Node Kiri (Sumber Tugas) -->
                            <div class="col-span-4 flex flex-col justify-center space-y-2.5">
                                <!-- Card Direktur -->
                                <div class="p-2 rounded-xl bg-white/95 dark:bg-[#18181D]/95 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 16l-3-9 6 3 4-6 4 6 6-3-3 9H5z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold text-zinc-800 dark:text-zinc-200 truncate leading-tight">Direktur</p>
                                        <p class="text-[9px] text-zinc-400">Level 1</p>
                                    </div>
                                </div>

                                <!-- Card Manager -->
                                <div class="p-2 rounded-xl bg-white/95 dark:bg-[#18181D]/95 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-lg bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold text-zinc-800 dark:text-zinc-200 truncate leading-tight">Manager</p>
                                        <p class="text-[9px] text-zinc-400">Level 3</p>
                                    </div>
                                </div>

                                <!-- Card Team Lead -->
                                <div class="p-2 rounded-xl bg-white/95 dark:bg-[#18181D]/95 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold text-zinc-800 dark:text-zinc-200 truncate leading-tight">Team Lead</p>
                                        <p class="text-[9px] text-zinc-400">Level 4</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Node Tengah (Hub Utama Assign Task: WARNA HIJAU DENGAN ANIMASI) -->
                            <div class="col-span-4 flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-emerald-600 to-green-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/35 animate-radar-hub-green cursor-pointer transition-transform hover:scale-110">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                                <span class="text-[10px] font-bold text-zinc-900 dark:text-zinc-100 mt-2 uppercase tracking-wider text-center">
                                    Assign Task
                                </span>
                            </div>

                            <!-- 3. Node Kanan (Hasil / Output - tidak ada text ungu) -->
                            <div class="col-span-4 flex flex-col justify-center space-y-1.5">
                                <!-- Notifikasi -->
                                <div class="p-1.5 px-2 rounded-xl bg-white/95 dark:bg-[#18181D]/95 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex items-center space-x-1.5">
                                    <div class="w-5 h-5 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                    <span class="text-[10px] font-semibold text-zinc-800 dark:text-zinc-200 truncate">Notifikasi</span>
                                </div>

                                <!-- Update Dashboard -->
                                <div class="p-1.5 px-2 rounded-xl bg-white/95 dark:bg-[#18181D]/95 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex items-center space-x-1.5">
                                    <div class="w-5 h-5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <span class="text-[10px] font-semibold text-zinc-800 dark:text-zinc-200 truncate">Update Dashboard</span>
                                </div>

                                <!-- Progress Tracking -->
                                <div class="p-1.5 px-2 rounded-xl bg-white/95 dark:bg-[#18181D]/95 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex items-center space-x-1.5">
                                    <div class="w-5 h-5 rounded-lg bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-[10px] font-semibold text-zinc-800 dark:text-zinc-200 truncate">Progress Tracking</span>
                                </div>

                                <!-- History Log -->
                                <div class="p-1.5 px-2 rounded-xl bg-white/95 dark:bg-[#18181D]/95 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex items-center space-x-1.5">
                                    <div class="w-5 h-5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-[10px] font-semibold text-zinc-800 dark:text-zinc-200 truncate">History Log</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Live Pipeline Board -->
                    <div class="mt-4 bg-white/95 dark:bg-[#121217]/95 rounded-2xl p-3 sm:p-3.5 border border-white/90 dark:border-zinc-800/80 shadow-sm relative z-10">
                        <!-- Header Board: Task Pipeline + ● Live -->
                        <div class="flex items-center justify-between pb-2 mb-2 border-b border-zinc-100 dark:border-zinc-800/80">
                            <span class="text-xs font-bold text-zinc-900 dark:text-white flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-zinc-700 dark:text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                </svg>
                                <span>Task Pipeline</span>
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-900/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-1.5"></span>
                                Live
                            </span>
                        </div>

                        <!-- 4 Kolom Sempit Berdampingan -->
                        <div class="grid grid-cols-4 gap-2">
                            <!-- Kolom 1: Belum Dikerjakan -->
                            <div class="bg-zinc-50/80 dark:bg-zinc-900/60 p-2 rounded-xl border border-zinc-200/60 dark:border-zinc-800/60">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[9px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-tight truncate">Pending</span>
                                    <span class="text-[10px] font-extrabold text-zinc-700 dark:text-zinc-300 px-1 rounded bg-zinc-200/70 dark:bg-zinc-800">5</span>
                                </div>
                                <div class="flex items-center -space-x-1.5 overflow-hidden py-0.5">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-zinc-300 text-zinc-700 text-[8px] font-bold">AF</span>
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-zinc-400 text-white text-[8px] font-bold">CL</span>
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-zinc-500 text-white text-[8px] font-bold">BS</span>
                                </div>
                            </div>

                            <!-- Kolom 2: Sedang Dikerjakan -->
                            <div class="bg-blue-50/50 dark:bg-blue-950/20 p-2 rounded-xl border border-blue-200/50 dark:border-blue-900/40">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[9px] font-bold text-blue-700 dark:text-blue-300 uppercase tracking-tight truncate">Proses</span>
                                    <span class="text-[10px] font-extrabold text-blue-700 dark:text-blue-300 px-1 rounded bg-blue-100 dark:bg-blue-900/50">8</span>
                                </div>
                                <div class="flex items-center -space-x-1.5 overflow-hidden py-0.5">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-blue-600 text-white text-[8px] font-bold">FA</span>
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-blue-500 text-white text-[8px] font-bold">RR</span>
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-cyan-600 text-white text-[8px] font-bold">DA</span>
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-slate-700 text-white text-[8px] font-bold">DP</span>
                                </div>
                            </div>

                            <!-- Kolom 3: Review -->
                            <div class="bg-amber-50/50 dark:bg-amber-950/20 p-2 rounded-xl border border-amber-200/50 dark:border-amber-900/40">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[9px] font-bold text-amber-700 dark:text-amber-300 uppercase tracking-tight truncate">Review</span>
                                    <span class="text-[10px] font-extrabold text-amber-700 dark:text-amber-300 px-1 rounded bg-amber-100 dark:bg-amber-900/50">2</span>
                                </div>
                                <div class="flex items-center -space-x-1.5 overflow-hidden py-0.5">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-amber-500 text-white text-[8px] font-bold">HG</span>
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-orange-500 text-white text-[8px] font-bold">SN</span>
                                </div>
                            </div>

                            <!-- Kolom 4: Selesai -->
                            <div class="bg-emerald-50/50 dark:bg-emerald-950/20 p-2 rounded-xl border border-emerald-200/50 dark:border-emerald-900/40">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[9px] font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-tight truncate">Selesai</span>
                                    <span class="text-[10px] font-extrabold text-emerald-700 dark:text-emerald-300 px-1 rounded bg-emerald-100 dark:bg-emerald-900/50">12</span>
                                </div>
                                <div class="flex items-center -space-x-1.5 overflow-hidden py-0.5">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-emerald-600 text-white text-[8px] font-bold">BP</span>
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-teal-600 text-white text-[8px] font-bold">BW</span>
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-emerald-500 text-white text-[8px] font-bold">AM</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
