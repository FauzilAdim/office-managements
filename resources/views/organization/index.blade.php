@extends('layouts.app')

@section('title', 'Struktur Organisasi')
@section('page_title', 'Struktur Organisasi & Hierarki')

@section('content')
<div class="space-y-6" x-data="{ viewMode: 'tree', activeDivision: 'all' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Hierarki & Garis Pelaporan Organisasi</h2>
            <p class="text-xs text-slate-500">
                Visualisasi hubungan kerja: Direktur &rarr; Vice President &rarr; Manager &rarr; Team Lead &rarr; Staff
            </p>
        </div>

        <!-- Mode Toggle -->
        <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-semibold">
            <button @click="viewMode = 'tree'" :class="viewMode === 'tree' ? 'bg-white text-[#881337] shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-3.5 py-1.5 rounded-lg transition-all flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                </svg>
                <span>Diagram Pohon (Tree)</span>
            </button>
            <button @click="viewMode = 'division'" :class="viewMode === 'division' ? 'bg-white text-[#881337] shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-3.5 py-1.5 rounded-lg transition-all flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Per Departemen</span>
            </button>
        </div>
    </div>

    <!-- Hierarchy Legend Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap items-center justify-between gap-3 text-xs">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tingkatan Hierarki:</span>
        <div class="flex flex-wrap items-center gap-3">
            <span class="flex items-center space-x-1.5">
                <span class="w-3 h-3 rounded-full bg-[#881337]"></span>
                <span class="font-medium text-slate-700">Direktur (Level 1)</span>
            </span>
            <span class="flex items-center space-x-1.5">
                <span class="w-3 h-3 rounded-full bg-[#DC2626]"></span>
                <span class="font-medium text-slate-700">Vice President (Level 2)</span>
            </span>
            <span class="flex items-center space-x-1.5">
                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                <span class="font-medium text-slate-700">Manager (Level 3)</span>
            </span>
            <span class="flex items-center space-x-1.5">
                <span class="w-3 h-3 rounded-full bg-slate-600"></span>
                <span class="font-medium text-slate-700">Team Lead (Level 4)</span>
            </span>
            <span class="flex items-center space-x-1.5">
                <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                <span class="font-medium text-slate-700">Staff (Level 5)</span>
            </span>
        </div>
    </div>

    <!-- View Mode 1: Interactive Tree View -->
    <div x-show="viewMode === 'tree'" class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm overflow-x-auto min-h-[500px]">
        <div class="min-w-max flex justify-center py-6 px-4">
            @foreach($tree as $rootNode)
                @include('organization.tree-node', ['node' => $rootNode])
            @endforeach
        </div>
    </div>

    <!-- View Mode 2: Per Department Breakdown View -->
    <div x-show="viewMode === 'division'" class="space-y-6">
        <!-- Division Tabs -->
        <div class="flex items-center space-x-2 overflow-x-auto pb-2">
            <button @click="activeDivision = 'all'" :class="activeDivision === 'all' ? 'bg-[#881337] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-50'" class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 whitespace-nowrap transition-colors cursor-pointer">
                Semua Divisi
            </button>
            @foreach($divisions as $div)
                <button @click="activeDivision = '{{ $div->id }}'" :class="activeDivision === '{{ $div->id }}' ? 'bg-[#881337] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-50'" class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 whitespace-nowrap transition-colors cursor-pointer">
                    {{ $div->name }} ({{ $div->code }})
                </button>
            @endforeach
        </div>

        <!-- Division Cards -->
        <div class="space-y-6">
            @foreach($divisions as $div)
                <div x-show="activeDivision === 'all' || activeDivision === '{{ $div->id }}'" class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                        <div class="flex items-center space-x-3">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-100 text-[#881337]">
                                {{ $div->code }}
                            </span>
                            <h3 class="text-lg font-bold text-slate-900">{{ $div->name }}</h3>
                        </div>
                        <span class="text-xs text-slate-500">{{ $div->users->count() }} Anggota Terdaftar</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($div->users as $u)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <img src="{{ $u->avatarUrl() }}" alt="{{ $u->name }}" class="w-11 h-11 rounded-xl object-cover">
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-slate-900 truncate">
                                            <a href="{{ route('employees.show', $u) }}" class="hover:text-[#881337]">
                                                {{ $u->name }}
                                            </a>
                                        </h4>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $u->position }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5 truncate">
                                            Lapor ke: <strong class="text-slate-600">{{ $u->supervisor?->name ?? 'Direktur' }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700 shrink-0 ml-2">
                                    {{ $u->roleDisplayName() }}
                                </span>
                            </div>
                        @empty
                            <p class="col-span-full py-4 text-xs text-slate-400 text-center">Belum ada karyawan di divisi ini.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
