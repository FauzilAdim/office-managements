@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('page_title', 'Direktori Karyawan')

@section('content')
<div class="space-y-6 max-w-[1500px] mx-auto pb-16">
    <!-- Header (Consistent with WORKSPACE) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-zinc-100 uppercase">DAFTAR KARYAWAN</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">
                Direktori seluruh personil & wewenang struktur organisasi perusahaan
            </p>
        </div>

        <a 
            href="{{ route('organization.index') }}" 
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-slate-700 dark:text-zinc-300 bg-white/80 dark:bg-zinc-900/80 hover:bg-white dark:hover:bg-zinc-800 border border-white/80 dark:border-zinc-800 shadow-xs backdrop-blur-md transition-all hover:scale-105 active:scale-95 shrink-0"
        >
            <svg class="w-4 h-4 text-slate-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
            </svg>
            <span>Struktur Organisasi &rarr;</span>
        </a>
    </div>

    <!-- Filters & Search Toolbar (Frosted Glass Container) -->
    <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md p-4 sm:p-5 rounded-3xl border border-white/80 dark:border-zinc-800 shadow-xs">
        <form action="{{ route('employees.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-zinc-400 mb-1">Cari Nama / Email / Jabatan</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Ketik kata kunci..." 
                    class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50/80 dark:bg-zinc-800/80 border border-slate-200/80 dark:border-zinc-700/80 text-slate-900 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-zinc-400 mb-1">Filter Divisi / Departemen</label>
                <select 
                    name="division_id" 
                    class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50/80 dark:bg-zinc-800/80 border border-slate-200/80 dark:border-zinc-700/80 text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                >
                    <option value="">Semua Divisi</option>
                    @foreach($divisions as $div)
                        <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>
                            {{ $div->name }} ({{ $div->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button 
                    type="submit" 
                    class="flex-1 py-2 px-4 rounded-xl text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 shadow-sm transition-all active:scale-95 cursor-pointer"
                >
                    Terapkan
                </button>
                @if(request()->anyFilled(['search', 'division_id']))
                    <a 
                        href="{{ route('employees.index') }}" 
                        class="py-2 px-3.5 rounded-xl text-xs font-medium text-slate-600 dark:text-zinc-300 bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Employees Directory Grid (Consistent Framer-style Frosted Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($employees as $emp)
            @php
                $activeTask = $emp->relationLoaded('assignedTasks') ? $emp->assignedTasks->first() : $emp->currentActiveTask();
            @endphp
            <div class="bg-white/80 hover:bg-white/90 dark:bg-zinc-900/80 dark:hover:bg-zinc-900/95 backdrop-blur-md rounded-3xl p-5 sm:p-6 border border-white/80 hover:border-white dark:border-zinc-800/80 dark:hover:border-zinc-700 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.3)] hover:shadow-xl transition-all duration-200 hover:-translate-y-1 flex flex-col justify-between group">
                <div>
                    <!-- Avatar & Role Badge -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3.5">
                            <div class="relative shrink-0">
                                <img src="{{ $emp->avatarUrl() }}" alt="{{ $emp->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-white/90 dark:ring-zinc-800 shadow-sm">
                                <span class="absolute -bottom-1 -right-1 flex h-3.5 w-3.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 ring-2 ring-white dark:ring-zinc-900"></span>
                                </span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base font-bold text-slate-900 dark:text-zinc-100 truncate group-hover:text-black dark:group-hover:text-white leading-snug">
                                    <a href="{{ route('employees.show', $emp) }}">
                                        {{ $emp->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-zinc-400 truncate mt-0.5">{{ $emp->position }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border border-zinc-200/80 dark:border-zinc-700/80 mt-1.5">
                                    {{ $emp->division?->name ?? 'Head Office' }}
                                </span>
                            </div>
                        </div>

                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 whitespace-nowrap border border-zinc-200/80 dark:border-zinc-700/80">
                            {{ $emp->roleDisplayName() }}
                        </span>
                    </div>

                    <!-- Contact & Supervisor Details -->
                    <div class="space-y-1.5 text-xs text-slate-500 dark:text-zinc-400 py-3 border-t border-slate-100 dark:border-zinc-800/80">
                        <div class="flex items-center space-x-2 truncate">
                            <svg class="w-3.5 h-3.5 text-slate-400 dark:text-zinc-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">{{ $emp->email }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-3.5 h-3.5 text-slate-400 dark:text-zinc-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="truncate">Atasan: <strong class="text-slate-700 dark:text-zinc-200">{{ $emp->supervisor?->name ?? 'None (Top Level)' }}</strong></span>
                        </div>
                    </div>

                    <!-- Current Active Work Real-Time Snippet -->
                    <div class="mt-2 pt-3 border-t border-slate-100 dark:border-zinc-800/80">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1.5">Sedang Mengerjakan:</span>
                        @if($activeTask)
                            <div class="bg-white/70 dark:bg-zinc-950/70 p-3 rounded-2xl border border-slate-200/60 dark:border-zinc-800/80 shadow-xs">
                                <div class="flex items-center space-x-1.5 mb-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 truncate">Deep Work:</span>
                                </div>
                                <a href="{{ route('tasks.show', $activeTask) }}" class="text-xs font-semibold text-slate-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400 block line-clamp-1 leading-snug">
                                    {{ $activeTask->title }}
                                </a>
                                <div class="flex justify-between items-center mt-2 text-[10px] text-slate-500 dark:text-zinc-400">
                                    <span>Pemberi: {{ $activeTask->creator?->name }}</span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $activeTask->progress }}% Selesai</span>
                                </div>
                            </div>
                        @else
                            <div class="bg-slate-50/60 dark:bg-zinc-800/40 p-2.5 rounded-2xl text-center border border-slate-100 dark:border-zinc-800/60">
                                <span class="text-xs text-slate-400 dark:text-zinc-500 italic">Tidak ada tugas aktif saat ini</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Link -->
                <div class="mt-4 pt-3.5 border-t border-slate-100 dark:border-zinc-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-400 dark:text-zinc-500 text-[11px]">Bergabung: {{ $emp->join_date?->translatedFormat('M Y') ?? '-' }}</span>
                    <a href="{{ route('employees.show', $emp) }}" class="font-bold text-zinc-900 dark:text-zinc-100 hover:underline flex items-center gap-1">
                        <span>Lihat Profil</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-400 dark:text-zinc-500 bg-white/60 dark:bg-zinc-900/60 rounded-3xl border border-white/80 dark:border-zinc-800">
                Tidak ada data karyawan yang sesuai dengan kriteria.
            </div>
        @endforelse
    </div>
</div>
@endsection
