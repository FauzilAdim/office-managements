@extends('layouts.app')

@section('title', $task->title)
@section('page_title', 'Detail Tugas')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Breadcrumb & Top Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('tasks.index') }}" class="hover:text-blue-600">&larr; Kembali ke Daftar Tugas</a>
            <span>/</span>
            <span class="text-slate-700 dark:text-slate-300 font-semibold truncate max-w-xs">{{ $task->title }}</span>
        </div>

        <div class="flex items-center space-x-2">
            @if($canUpdate)
                <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 transition-colors">
                    <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Rincian
                </a>
            @endif

            @if($canDelete)
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-300 transition-colors border border-rose-200 dark:border-rose-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Main Hero Card -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700/80 shadow-sm">
        <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $task->status->badgeClasses() }}">
                <span class="w-2 h-2 rounded-full {{ $task->status->dotColor() }} mr-1.5"></span>
                {{ $task->status->label() }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $task->priority->badgeClasses() }}">
                Prioritas: {{ $task->priority->label() }}
            </span>
            @if($task->isOverdue())
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-500 text-white shadow-sm shadow-rose-500/20">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Melewati Batas Waktu
                </span>
            @endif
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $task->title }}</h1>

        <!-- Overall Progress Bar -->
        <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700/60">
            <div class="flex justify-between items-center text-xs font-bold mb-2">
                <span class="text-slate-500 uppercase tracking-wider">Tingkat Kemajuan (Progress)</span>
                <span class="text-blue-600 dark:text-blue-400 text-sm">{{ $task->progress }}%</span>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-700 h-3 rounded-full overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-3 rounded-full transition-all duration-500" style="width: {{ $task->progress }}%"></div>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Description, Update Form, Audit Log -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Deskripsi & Instruksi Tugas</h3>
                <div class="prose dark:prose-invert max-w-none text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed">
                    {{ $task->description ?: 'Tidak ada deskripsi rinci untuk tugas ini.' }}
                </div>
            </div>

            <!-- Update Status & Progress Form (Only for Authorized Users) -->
            @if($canUpdateStatus)
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm" x-data="{ currentProgress: {{ $task->progress }}, currentStatus: '{{ $task->status->value }}' }">
                    <div class="flex items-center space-x-2.5 pb-4 border-b border-slate-100 dark:border-slate-700/60 mb-5">
                        <span class="p-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Perbarui Status & Progres Pekerjaan</h3>
                            <p class="text-xs text-slate-500">Perubahan akan langsung dicatat ke dalam audit log riwayat tugas.</p>
                        </div>
                    </div>

                    <form action="{{ route('tasks.update-status', $task) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Status Selector -->
                            <div>
                                <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                                    Status Tugas <span class="text-rose-500">*</span>
                                </label>
                                <select id="status" name="status" x-model="currentStatus" @change="if(currentStatus === 'completed') currentProgress = 100; if(currentStatus === 'pending') currentProgress = 0;" required class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach($statuses as $st)
                                        <option value="{{ $st->value }}" {{ $task->status->value === $st->value ? 'selected' : '' }}>
                                            {{ $st->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Progress Slider -->
                            <div>
                                <div class="flex justify-between items-center mb-1.5">
                                    <label for="progress" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                        Progres Pengerjaan
                                    </label>
                                    <span class="text-xs font-bold text-blue-600 dark:text-blue-400" x-text="currentProgress + '%'"></span>
                                </div>
                                <input type="range" id="progress" name="progress" min="0" max="100" step="5" x-model="currentProgress" class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-blue-600">
                            </div>
                        </div>

                        <!-- Audit Log Note -->
                        <div>
                            <label for="note" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                                Catatan Perubahan (Audit Note)
                            </label>
                            <input type="text" id="note" name="note" placeholder="contoh: Selesai implementasi endpoint dan unit test..." class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all hover:scale-[1.01]">
                                Simpan Perubahan Status
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Audit Log / Riwayat Perubahan Status -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm">
                <div class="flex items-center space-x-2.5 pb-4 border-b border-slate-100 dark:border-slate-700/60 mb-5">
                    <span class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Riwayat Perubahan Status (Audit Trail)</h3>
                        <p class="text-xs text-slate-500">Log transparan setiap pembaruan status dan progres oleh staf atau atasan.</p>
                    </div>
                </div>

                <div class="flow-root">
                    <ul class="-mb-8">
                        @forelse($task->logs as $index => $log)
                            <li>
                                <div class="relative pb-8">
                                    @if(! $loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200 dark:bg-slate-700" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-600 dark:text-blue-400 flex items-center justify-center ring-8 ring-white dark:ring-slate-800 text-xs font-bold">
                                                {{ $loop->iteration }}
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $log->user?->name ?? 'Sistem' }}</span>
                                                    @if($log->from_status)
                                                        <span class="text-[11px] text-slate-400">&bull; status: <code class="text-slate-600 dark:text-slate-300">{{ $log->from_status }}</code> &rarr; <strong class="text-blue-600 dark:text-blue-400">{{ $log->to_status }}</strong></span>
                                                    @else
                                                        <span class="text-[11px] text-slate-400">&bull; status awal: <strong class="text-blue-600 dark:text-blue-400">{{ $log->to_status }}</strong></span>
                                                    @endif
                                                    @if($log->to_progress !== null)
                                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 font-semibold">{{ $log->to_progress }}%</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 italic bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-100 dark:border-slate-800">
                                                    "{{ $log->note }}"
                                                </p>
                                            </div>
                                            <div class="text-right text-[11px] whitespace-nowrap text-slate-400">
                                                {{ $log->created_at->translatedFormat('d M Y, H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <p class="text-xs text-slate-400 py-4 text-center">Belum ada riwayat aktivitas log.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Assignee, Assigner, Deadlines -->
        <div class="space-y-6">
            <!-- Assignee Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-3">Penerima Tugas (Assignee)</span>
                <div class="flex items-center space-x-3.5">
                    <img src="{{ $task->assignee?->avatarUrl() }}" alt="{{ $task->assignee?->name }}" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-blue-500/20">
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $task->assignee?->name }}</h4>
                        <p class="text-xs text-slate-500 truncate">{{ $task->assignee?->position }}</p>
                        <span class="inline-flex mt-1 text-[10px] font-semibold px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300">
                            {{ $task->assignee?->division?->name ?? 'Head Office' }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/60 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Email:</span>
                        <span class="text-slate-800 dark:text-slate-200 font-medium truncate max-w-[160px]">{{ $task->assignee?->email }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>No. HP:</span>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $task->assignee?->phone ?? '-' }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60">
                    <a href="{{ route('employees.show', $task->assignee) }}" class="block text-center text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                        Lihat Profil Karyawan Lengkap &rarr;
                    </a>
                </div>
            </div>

            <!-- Assigner Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-3">Pemberi Tugas (Assigned By)</span>
                <div class="flex items-center space-x-3.5">
                    <img src="{{ $task->creator?->avatarUrl() }}" alt="{{ $task->creator?->name }}" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-slate-300 dark:ring-slate-700">
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $task->creator?->name }}</h4>
                        <p class="text-xs text-slate-500 truncate">{{ $task->creator?->position }}</p>
                        <span class="inline-flex mt-1 text-[10px] font-semibold px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                            {{ $task->creator?->roleDisplayName() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Timing Details Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm space-y-3.5">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Informasi Waktu</span>

                <div>
                    <span class="text-[11px] text-slate-400 block">Batas Waktu (Deadline):</span>
                    <span class="text-xs font-bold {{ $task->isOverdue() ? 'text-rose-500' : 'text-slate-800 dark:text-slate-200' }}">
                        {{ $task->deadline->translatedFormat('l, d F Y - H:i') }}
                    </span>
                </div>

                @if($task->started_at)
                    <div>
                        <span class="text-[11px] text-slate-400 block">Mulai Dikerjakan:</span>
                        <span class="text-xs font-medium text-slate-800 dark:text-slate-200">
                            {{ $task->started_at->translatedFormat('d F Y, H:i') }} ({{ $task->started_at->diffForHumans() }})
                        </span>
                    </div>
                @endif

                @if($task->completed_at)
                    <div>
                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold block">Tanggal Selesai:</span>
                        <span class="text-xs font-bold text-emerald-700 dark:text-emerald-300">
                            {{ $task->completed_at->translatedFormat('d F Y, H:i') }}
                        </span>
                    </div>
                @endif

                <div>
                    <span class="text-[11px] text-slate-400 block">Dibuat Pada:</span>
                    <span class="text-xs text-slate-600 dark:text-slate-400">
                        {{ $task->created_at->translatedFormat('d F Y, H:i') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
