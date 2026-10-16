@extends('layouts.app')

@section('title', 'Profil Karyawan - ' . $employee->name)
@section('page_title', 'Profil & Riwayat Kerja Karyawan')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Breadcrumb & Edit Button -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('employees.index') }}" class="hover:text-blue-600">&larr; Kembali ke Direktori</a>
            <span>/</span>
            <span class="text-slate-700 dark:text-slate-300 font-semibold">{{ $employee->name }}</span>
        </div>

        @can('update', $employee)
            <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 transition-colors">
                <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Profil
            </a>
        @endcan
    </div>

    <!-- Employee Hero Card -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700/80 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                <img src="{{ $employee->avatarUrl() }}" alt="{{ $employee->name }}" class="w-24 h-24 rounded-3xl object-cover ring-4 ring-blue-500/20 shadow-md">
                <div>
                    <div class="flex items-center space-x-2.5 mb-1">
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ $employee->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            {{ $employee->roleDisplayName() }}
                        </span>
                    </div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $employee->position }}</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold mt-0.5">
                        Divisi: {{ $employee->division?->name ?? 'Head Office (Direksi)' }}
                    </p>
                </div>
            </div>

            <!-- Atasan Langsung & Bergabung -->
            <div class="bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-xs space-y-2 min-w-[240px]">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Atasan Langsung (Supervisor):</span>
                    @if($employee->supervisor)
                        <a href="{{ route('employees.show', $employee->supervisor) }}" class="font-bold text-slate-900 dark:text-white hover:text-blue-600 flex items-center space-x-1.5 mt-0.5">
                            <img src="{{ $employee->supervisor->avatarUrl() }}" class="w-5 h-5 rounded-full">
                            <span>{{ $employee->supervisor->name }}</span>
                        </a>
                    @else
                        <span class="font-bold text-slate-700 dark:text-slate-300">Pimpinan Tertinggi (Direktur)</span>
                    @endif
                </div>

                <div class="pt-2 border-t border-slate-200/60 dark:border-slate-800">
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tanggal Bergabung:</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">
                        {{ $employee->join_date?->translatedFormat('d F Y') ?? '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Info & Hierarchy Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Contact & Address -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Kontak & Domisili</h3>

            <div>
                <span class="text-[11px] text-slate-400 block">Email Kantor:</span>
                <span class="text-xs font-semibold text-slate-900 dark:text-white">{{ $employee->email }}</span>
            </div>

            <div>
                <span class="text-[11px] text-slate-400 block">Nomor Telepon / WhatsApp:</span>
                <span class="text-xs font-semibold text-slate-900 dark:text-white">{{ $employee->phone ?: 'Belum diisi' }}</span>
            </div>

            <div>
                <span class="text-[11px] text-slate-400 block">Alamat Domisili:</span>
                <p class="text-xs text-slate-700 dark:text-slate-300 mt-0.5 leading-relaxed">{{ $employee->address ?: 'Belum diisi' }}</p>
            </div>
        </div>

        <!-- Task Summary Stats -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Statistik Kinerja Tugas</h3>

            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="bg-slate-50 dark:bg-slate-900/60 p-3 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <span class="text-xs text-slate-500">Total Tugas</span>
                    <p class="text-xl font-black text-slate-900 dark:text-white mt-0.5">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-950/40 p-3 rounded-2xl border border-emerald-100 dark:border-emerald-800">
                    <span class="text-xs text-emerald-600">Selesai</span>
                    <p class="text-xl font-black text-emerald-600 mt-0.5">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-950/40 p-3 rounded-2xl border border-blue-100 dark:border-blue-800">
                    <span class="text-xs text-blue-600">Sedang Berjalan</span>
                    <p class="text-xl font-black text-blue-600 mt-0.5">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-950/40 p-3 rounded-2xl border border-amber-100 dark:border-amber-800">
                    <span class="text-xs text-amber-600">Review</span>
                    <p class="text-xl font-black text-amber-600 mt-0.5">{{ $stats['review'] }}</p>
                </div>
            </div>
        </div>

        <!-- Subordinates List (Bawahan Langsung) -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                Bawahan Langsung ({{ $employee->subordinates->count() }})
            </h3>

            <div class="space-y-2.5 max-h-48 overflow-y-auto pr-1">
                @forelse($employee->subordinates as $sub)
                    <a href="{{ route('employees.show', $sub) }}" class="flex items-center space-x-2.5 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors">
                        <img src="{{ $sub->avatarUrl() }}" class="w-8 h-8 rounded-lg object-cover">
                        <div class="min-w-0 flex-1">
                            <h5 class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $sub->name }}</h5>
                            <p class="text-[11px] text-slate-400 truncate">{{ $sub->position }}</p>
                        </div>
                    </a>
                @empty
                    <p class="text-xs text-slate-400 italic py-4 text-center">Tidak memiliki bawahan langsung (Individual Contributor).</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Riwayat Pekerjaan / Tugas yang Pernah Dikerjakan -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700/80 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700/60 mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Riwayat Pekerjaan & Tugas</h3>
                <p class="text-xs text-slate-500">Daftar seluruh tugas yang didelegasikan dan dikerjakan oleh {{ $employee->name }}</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                {{ $task_history->count() }} Tugas Tercatat
            </span>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-700/60">
            @forelse($task_history as $task)
                <div class="py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center space-x-2 mb-1.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $task->status->badgeClasses() }}">
                                {{ $task->status->label() }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $task->priority->badgeClasses() }}">
                                {{ $task->priority->label() }}
                            </span>
                            @if($task->isOverdue())
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500 text-white">
                                    Overdue
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('tasks.show', $task) }}" class="text-base font-bold text-slate-900 dark:text-white hover:text-blue-600 block">
                            {{ $task->title }}
                        </a>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $task->description }}</p>

                        <div class="mt-2 flex items-center space-x-3 text-xs text-slate-500">
                            <span>Diberikan oleh: <strong class="text-slate-700 dark:text-slate-300">{{ $task->creator?->name }}</strong></span>
                            <span>&bull;</span>
                            <span>Deadline: {{ $task->deadline->translatedFormat('d M Y') }}</span>
                            @if($task->completed_at)
                                <span>&bull;</span>
                                <span class="text-emerald-600 font-semibold">Selesai: {{ $task->completed_at->translatedFormat('d M Y') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="text-right w-24">
                            <span class="text-xs font-bold text-blue-600">{{ $task->progress }}%</span>
                            <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden mt-1">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $task->progress }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('tasks.show', $task) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 transition-colors">
                            Rincian &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400 text-xs">
                    Karyawan ini belum memiliki riwayat tugas tercatat.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
