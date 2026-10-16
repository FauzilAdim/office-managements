@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('page_title', 'Direktori Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Karyawan</h2>
            <p class="text-xs text-slate-500">
                Karyawan yang berada dalam cakupan wewenang atau departemen Anda
            </p>
        </div>

        <a href="{{ route('organization.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-bold text-[#881337] bg-rose-50 border border-rose-200 hover:bg-rose-100 transition-colors shadow-xs">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
            </svg>
            Visualisasi Struktur Organisasi &rarr;
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('employees.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Cari Nama / Email / Jabatan</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#881337] focus:border-[#881337]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Filter Divisi / Departemen</label>
                <select name="division_id" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#881337] focus:border-[#881337]">
                    <option value="">Semua Divisi</option>
                    @foreach($divisions as $div)
                        <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>
                            {{ $div->name }} ({{ $div->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-[#DC2626] to-[#881337] hover:from-[#EF4444] hover:to-[#991B1B] shadow-md transition-all active:scale-95 cursor-pointer">
                    Terapkan
                </button>
                @if(request()->anyFilled(['search', 'division_id']))
                    <a href="{{ route('employees.index') }}" class="py-2 px-3 rounded-xl text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Employees Directory Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($employees as $emp)
            @php
                $activeTask = $emp->currentActiveTask();
            @endphp
            <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm hover:shadow-md hover:border-rose-400 transition-all flex flex-col justify-between">
                <div>
                    <!-- Avatar & Role Badge -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3.5">
                            <img src="{{ $emp->avatarUrl() }}" alt="{{ $emp->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-rose-500/20 shadow-sm">
                            <div class="min-w-0">
                                <h3 class="text-base font-bold text-slate-900 truncate">
                                    <a href="{{ route('employees.show', $emp) }}" class="hover:text-[#881337]">
                                        {{ $emp->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 truncate">{{ $emp->position }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-[#881337] border border-rose-200/80 mt-1">
                                    {{ $emp->division?->name ?? 'Head Office' }}
                                </span>
                            </div>
                        </div>

                        <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 whitespace-nowrap">
                            {{ $emp->roleDisplayName() }}
                        </span>
                    </div>

                    <!-- Contact & Supervisor -->
                    <div class="space-y-1.5 text-xs text-slate-500 py-3 border-t border-slate-100">
                        <div class="flex items-center space-x-2 truncate">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">{{ $emp->email }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="truncate">Atasan: <strong class="text-slate-700">{{ $emp->supervisor?->name ?? 'None (Top Level)' }}</strong></span>
                        </div>
                    </div>

                    <!-- Current Active Work Real-Time Snippet -->
                    <div class="mt-2 pt-3 border-t border-slate-100">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Status Pekerjaan:</span>
                        @if($activeTask)
                            <div class="bg-rose-50/60 p-2.5 rounded-xl border border-rose-200/80">
                                <div class="flex items-center space-x-1.5 mb-1">
                                    <span class="w-2 h-2 rounded-full bg-[#DC2626] animate-pulse"></span>
                                    <span class="text-[10px] font-bold text-[#881337] truncate">Sedang Dikerjakan:</span>
                                </div>
                                <a href="{{ route('tasks.show', $activeTask) }}" class="text-xs font-semibold text-slate-900 hover:text-[#881337] block line-clamp-1">
                                    {{ $activeTask->title }}
                                </a>
                                <div class="flex justify-between items-center mt-1.5 text-[10px] text-slate-500">
                                    <span>Pemberi: {{ $activeTask->creator?->name }}</span>
                                    <span class="font-bold text-[#881337]">{{ $activeTask->progress }}%</span>
                                </div>
                            </div>
                        @else
                            <div class="bg-slate-50 p-2 rounded-xl text-center">
                                <span class="text-xs text-slate-400 italic">Tidak ada tugas aktif saat ini</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Link -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 text-[11px]">Bergabung: {{ $emp->join_date?->translatedFormat('M Y') ?? '-' }}</span>
                    <a href="{{ route('employees.show', $emp) }}" class="font-semibold text-[#881337] hover:underline">
                        Lihat Profil & Riwayat &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">
                Tidak ada data karyawan yang sesuai dengan kriteria.
            </div>
        @endforelse
    </div>
</div>
@endsection
