@extends('layouts.app')

@section('title', 'Struktur Organisasi')
@section('page_title', 'Struktur Organisasi & Hierarki')

@section('content')
<div class="space-y-6 max-w-[1500px] mx-auto pb-16" x-data="{ viewMode: 'tree', activeDivision: 'all' }">
    <!-- Header (Consistent with WORKSPACE) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-zinc-100 uppercase">STRUKTUR ORGANISASI</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">
                Visualisasi garis pelaporan: Direktur &rarr; Vice President &rarr; Manager &rarr; Team Lead &rarr; Staff
            </p>
        </div>

        <!-- Mode Toggle (Pill Switcher) -->
        <div class="flex items-center bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md p-1 rounded-full border border-white/80 dark:border-zinc-800 shadow-xs text-xs font-semibold shrink-0">
            <button 
                @click="viewMode = 'tree'" 
                :class="viewMode === 'tree' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-sm' : 'text-slate-500 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-zinc-100'" 
                class="px-4 py-1.5 rounded-full transition-all flex items-center space-x-1.5 cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                </svg>
                <span>Diagram Pohon</span>
            </button>
            <button 
                @click="viewMode = 'division'" 
                :class="viewMode === 'division' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-sm' : 'text-slate-500 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-zinc-100'" 
                class="px-4 py-1.5 rounded-full transition-all flex items-center space-x-1.5 cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Per Departemen</span>
            </button>
        </div>
    </div>

    <!-- Hierarchy Legend Bar (Consistent Frosted Glass Pill) -->
    <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md p-4 rounded-3xl border border-white/80 dark:border-zinc-800 shadow-xs flex flex-wrap items-center justify-between gap-3 text-xs">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Tingkatan Wewenang:</span>
        <div class="flex flex-wrap items-center gap-4">
            <span class="flex items-center space-x-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                <span class="font-medium text-slate-700 dark:text-zinc-300">Direktur (L1)</span>
            </span>
            <span class="flex items-center space-x-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <span class="font-medium text-slate-700 dark:text-zinc-300">Vice President (L2)</span>
            </span>
            <span class="flex items-center space-x-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="font-medium text-slate-700 dark:text-zinc-300">Manager (L3)</span>
            </span>
            <span class="flex items-center space-x-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span class="font-medium text-slate-700 dark:text-zinc-300">Team Lead (L4)</span>
            </span>
            <span class="flex items-center space-x-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-400 dark:bg-zinc-500"></span>
                <span class="font-medium text-slate-700 dark:text-zinc-300">Staff (L5)</span>
            </span>
        </div>
    </div>

    <!-- View Mode 1: Interactive Tree View (Frosted Canvas) -->
    <div x-show="viewMode === 'tree'" class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-10 border border-white/80 dark:border-zinc-800 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.3)] overflow-x-auto min-h-[500px]">
        <div class="min-w-max flex justify-center py-6 px-4">
            @foreach($tree as $rootNode)
                @include('organization.tree-node', ['node' => $rootNode])
            @endforeach
        </div>
    </div>

    <!-- View Mode 2: Per Department Breakdown View -->
    <div x-show="viewMode === 'division'" class="space-y-6">
        <!-- Division Tabs -->
        <div class="flex items-center space-x-2 overflow-x-auto pb-1">
            <button 
                @click="activeDivision = 'all'" 
                :class="activeDivision === 'all' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 font-bold shadow-sm' : 'bg-white/80 dark:bg-zinc-900/80 text-slate-700 dark:text-zinc-300 hover:bg-white dark:hover:bg-zinc-800 border border-white/80 dark:border-zinc-800'" 
                class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-colors cursor-pointer"
            >
                Semua Divisi
            </button>
            @foreach($divisions as $div)
                <button 
                    @click="activeDivision = '{{ $div->id }}'" 
                    :class="activeDivision === '{{ $div->id }}' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 font-bold shadow-sm' : 'bg-white/80 dark:bg-zinc-900/80 text-slate-700 dark:text-zinc-300 hover:bg-white dark:hover:bg-zinc-800 border border-white/80 dark:border-zinc-800'" 
                    class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-colors cursor-pointer"
                >
                    {{ $div->name }} ({{ $div->code }})
                </button>
            @endforeach
        </div>

        <!-- Division Cards -->
        <div class="space-y-6">
            @foreach($divisions as $div)
                <div x-show="activeDivision === 'all' || activeDivision === '{{ $div->id }}'" class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md rounded-3xl p-6 border border-white/80 dark:border-zinc-800 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.3)]">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-zinc-800 mb-5">
                        <div class="flex items-center space-x-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border border-zinc-200/80 dark:border-zinc-700/80">
                                {{ $div->code }}
                            </span>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-zinc-100">{{ $div->name }}</h3>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-zinc-400 font-medium">{{ $div->users->count() }} Anggota Terdaftar</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($div->users as $u)
                            <div class="p-4 rounded-2xl bg-white/70 dark:bg-zinc-800/60 border border-slate-200/60 dark:border-zinc-700/60 hover:bg-white dark:hover:bg-zinc-800 transition-colors flex items-center justify-between shadow-2xs">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <img src="{{ $u->avatarUrl() }}" alt="{{ $u->name }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-white/90 dark:ring-zinc-700">
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-zinc-100 truncate">
                                            <a href="{{ route('employees.show', $u) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ $u->name }}
                                            </a>
                                        </h4>
                                        <p class="text-[11px] text-slate-500 dark:text-zinc-400 truncate">{{ $u->position }}</p>
                                        <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-0.5 truncate">
                                            Lapor ke: <strong class="text-slate-600 dark:text-zinc-300">{{ $u->supervisor?->name ?? 'Direktur' }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 shrink-0 ml-2 border border-zinc-200/60 dark:border-zinc-600/60">
                                    {{ $u->roleDisplayName() }}
                                </span>
                            </div>
                        @empty
                            <p class="col-span-full py-6 text-xs text-slate-400 dark:text-zinc-500 text-center">Belum ada karyawan di divisi ini.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
