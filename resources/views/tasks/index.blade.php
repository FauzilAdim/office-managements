@extends('layouts.app')

@section('title', 'Manajemen Tugas')

@section('content')
<div 
    x-data="{
        activeTab: 'all',
        viewMode: 'kanban',
        draggedTaskId: null,
        dragOverColumn: null,
        isUpdating: false,

        async moveTask(taskId, targetStatus) {
            if (!taskId || this.isUpdating) return;
            this.isUpdating = true;
            try {
                let response = await fetch('/tasks/' + taskId + '/status', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: targetStatus })
                });
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Gagal memperbarui status tugas.');
                    this.isUpdating = false;
                }
            } catch (err) {
                console.error(err);
                this.isUpdating = false;
            }
        },

        taskMatchesTab(status, priority, isCompletedThisMonth = false) {
            if (this.activeTab === 'all') return true;
            if (this.activeTab === 'target') return status === 'pending' || status === 'in_progress';
            if (this.activeTab === 'review') return status === 'review';
            if (this.activeTab === 'favorite') return priority === 'high' || priority === 'urgent';
            return true;
        }
    }"
    class="space-y-6"
>
    <!-- Top Bar Khusus Manajemen Tugas: Breadcrumb + Judul Besar + Avatar Stack + Actions + Tabs Navigasi -->
    <div class="rounded-2xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800 p-5 shadow-2xs transition-colors">
        <!-- 1. BARIS BREADCRUMB KECIL (UPPERCASE DENGAN SEPARATOR ›) -->
        <div class="flex items-center space-x-2 text-[11px] font-semibold tracking-wider text-zinc-400 dark:text-zinc-500 uppercase leading-none mb-2">
            <span>Tugas</span>
            <span class="text-zinc-300 dark:text-zinc-600">&rsaquo;</span>
            <span>{{ auth()->user()->division ? auth()->user()->division->name : 'Seluruh Divisi' }}</span>
            <span class="text-zinc-300 dark:text-zinc-600">&rsaquo;</span>
            <span class="text-zinc-600 dark:text-zinc-300">Sprint Operasional & IT Systems</span>
        </div>

        <!-- 2. BARIS JUDUL BESAR + AVATAR STACK + TOMBOL KANAN -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3">
            <!-- Left: Judul Besar & Avatar Stack -->
            <div class="flex flex-wrap items-center gap-3.5">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white leading-tight">
                    Sprint Operasional & Task Board
                </h1>

                <!-- Team Avatar Stack Overlapping + Tambah Anggota -->
                <div class="flex items-center space-x-2 pl-2 border-l border-zinc-200 dark:border-zinc-800">
                    <div class="flex -space-x-1.5 overflow-hidden">
                        @foreach($teamMembers->take(4) as $member)
                            <img 
                                src="{{ $member->avatarUrl() }}" 
                                alt="{{ $member->name }}" 
                                title="{{ $member->name }} ({{ $member->roleDisplayName() }})"
                                class="inline-block h-6 w-6 rounded-full ring-2 ring-white dark:ring-zinc-900 object-cover"
                            >
                        @endforeach
                    </div>

                    @if($teamMembers->count() > 4)
                        <span class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">
                            +{{ $teamMembers->count() - 4 }} anggota
                        </span>
                    @else
                        <span class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">
                            {{ $teamMembers->count() }} anggota
                        </span>
                    @endif

                    <!-- Tombol kecil bulat dengan icon "+" -->
                    @can('create', App\Models\Task::class)
                        <a 
                            href="{{ route('tasks.create') }}" 
                            title="Tugaskan ke Anggota Baru"
                            class="w-5 h-5 rounded-full border border-dashed border-zinc-300 dark:border-zinc-600 flex items-center justify-center text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:border-zinc-400 transition-colors"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Right: Tombol "Bagikan" (Ghost) & "Tugas Baru" (Solid Filled) -->
            <div class="flex items-center space-x-2.5 shrink-0">
                <!-- Tombol Bagikan -->
                <button 
                    type="button" 
                    @click="navigator.clipboard.writeText(window.location.href); alert('Tautan papan tugas berhasil disalin ke clipboard!')"
                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-700 dark:text-zinc-200 bg-white dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-750 transition-colors shadow-2xs cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5 mr-1.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                    Bagikan
                </button>

                <!-- Tombol Tugas Baru (Matching WORKSPACE Button Style) -->
                @can('create', App\Models\Task::class)
                    <a 
                        href="{{ route('tasks.create') }}" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 shadow-sm transition-all hover:scale-105 active:scale-95 cursor-pointer"
                    >
                        <span class="text-sm leading-none">+</span>
                        <span>Buat Tugas Baru</span>
                    </a>
                @endcan
            </div>
        </div>

        <!-- 3. TAB NAVIGASI HORIZONTAL DENGAN UNDERLINE AKTIF & VIEW TOGGLE (Consistent Framer Style) -->
        <div class="flex items-center justify-between border-t border-zinc-100 dark:border-zinc-800/80 pt-1 -mx-5 px-5 mt-2">
            <!-- Tabs -->
            <div class="flex items-center space-x-6 text-xs overflow-x-auto no-scrollbar">
                <!-- Tab: Semua Tugas -->
                <button 
                    type="button"
                    @click="activeTab = 'all'"
                    :class="activeTab === 'all' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white font-bold' : 'border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200'"
                    class="py-2.5 border-b-2 transition-colors whitespace-nowrap flex items-center space-x-1 cursor-pointer"
                >
                    <span>Semua Tugas</span>
                    <span class="text-zinc-400 dark:text-zinc-500 font-normal">({{ $totalTasksCount }})</span>
                </button>

                <!-- Tab: Target -->
                <button 
                    type="button"
                    @click="activeTab = 'target'"
                    :class="activeTab === 'target' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white font-bold' : 'border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200'"
                    class="py-2.5 border-b-2 transition-colors whitespace-nowrap flex items-center space-x-1 cursor-pointer"
                >
                    <span>Target</span>
                    <span class="text-zinc-400 dark:text-zinc-500 font-normal">({{ $targetTasksCount }})</span>
                </button>

                <!-- Tab: Dipantau -->
                <button 
                    type="button"
                    @click="activeTab = 'review'"
                    :class="activeTab === 'review' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white font-bold' : 'border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200'"
                    class="py-2.5 border-b-2 transition-colors whitespace-nowrap flex items-center space-x-1 cursor-pointer"
                >
                    <span>Dipantau</span>
                    <span class="text-zinc-400 dark:text-zinc-500 font-normal">({{ $reviewTasksCount }})</span>
                </button>

                <!-- Tab: Favorit -->
                <button 
                    type="button"
                    @click="activeTab = 'favorite'"
                    :class="activeTab === 'favorite' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white font-bold' : 'border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200'"
                    class="py-2.5 border-b-2 transition-colors whitespace-nowrap flex items-center space-x-1 cursor-pointer"
                >
                    <span>Prioritas Tinggi</span>
                    <span class="text-zinc-400 dark:text-zinc-500 font-normal">({{ $favoriteTasksCount }})</span>
                </button>
            </div>

            <!-- Di ujung kanan baris yang sama: 2 icon toggle tampilan (grid/kanban view / list view) -->
            <div class="flex items-center space-x-1 shrink-0 pl-4 border-l border-zinc-100 dark:border-zinc-800/80">
                <button 
                    type="button"
                    @click="viewMode = 'kanban'"
                    :class="viewMode === 'kanban' ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-semibold' : 'text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200'"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    title="Tampilan Papan Kanban"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z" />
                    </svg>
                </button>

                <button 
                    type="button"
                    @click="viewMode = 'list'"
                    :class="viewMode === 'list' ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-semibold' : 'text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200'"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    title="Tampilan Tabel List"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- 4. KANBAN BOARD (KONTEN UTAMA) -->
    <div x-show="viewMode === 'kanban'" class="transition-opacity duration-150">
        <!-- 4 Kolom Berdampingan (Grid Horizontal, Scrollable di Mobile) -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 items-start">
            
            <!-- ============================================== -->
            <!-- KOLOM 1: BELUM DIKERJAKAN (Dot Abu-abu)       -->
            <!-- ============================================== -->
            <div 
                @dragover.prevent="dragOverColumn = 'pending'"
                @dragleave="dragOverColumn = null"
                @drop="moveTask(draggedTaskId, 'pending'); dragOverColumn = null"
                :class="dragOverColumn === 'pending' ? 'ring-2 ring-indigo-500 bg-indigo-50/20 dark:bg-indigo-950/20' : ''"
                class="rounded-2xl bg-zinc-50/70 dark:bg-zinc-900/40 border border-zinc-200/80 dark:border-zinc-800 p-3.5 transition-all duration-150"
            >
                <!-- Header Kolom -->
                <div class="flex items-center justify-between pb-3 px-1">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-zinc-400"></span>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-zinc-100">
                            Belum Dikerjakan
                        </h3>
                        <span class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500">
                            ({{ $pendingTasks->count() }})
                        </span>
                    </div>
                    <div class="flex items-center space-x-1 text-zinc-400">
                        @can('create', App\Models\Task::class)
                            <a href="{{ route('tasks.create') }}" class="p-1 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors" title="Tambah Tugas Baru">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                        @endcan
                        <button type="button" class="p-1 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors" title="Opsi Kolom">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- List Card Tugas di Kolom 1 -->
                <div class="space-y-3 min-h-[140px]">
                    @forelse($pendingTasks as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="py-8 text-center text-zinc-400 text-xs border border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl">
                            Belum ada tugas
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ============================================== -->
            <!-- KOLOM 2: SEDANG DIKERJAKAN (Dot Biru)          -->
            <!-- ============================================== -->
            <div 
                @dragover.prevent="dragOverColumn = 'in_progress'"
                @dragleave="dragOverColumn = null"
                @drop="moveTask(draggedTaskId, 'in_progress'); dragOverColumn = null"
                :class="dragOverColumn === 'in_progress' ? 'ring-2 ring-indigo-500 bg-indigo-50/20 dark:bg-indigo-950/20' : ''"
                class="rounded-2xl bg-zinc-50/70 dark:bg-zinc-900/40 border border-zinc-200/80 dark:border-zinc-800 p-3.5 transition-all duration-150"
            >
                <!-- Header Kolom -->
                <div class="flex items-center justify-between pb-3 px-1">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-zinc-100">
                            Sedang Dikerjakan
                        </h3>
                        <span class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500">
                            ({{ $inProgressTasks->count() }})
                        </span>
                    </div>
                    <div class="flex items-center space-x-1 text-zinc-400">
                        @can('create', App\Models\Task::class)
                            <a href="{{ route('tasks.create') }}" class="p-1 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors" title="Tambah Tugas Baru">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                        @endcan
                        <button type="button" class="p-1 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors" title="Opsi Kolom">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- List Card Tugas di Kolom 2 -->
                <div class="space-y-3 min-h-[140px]">
                    @forelse($inProgressTasks as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="py-8 text-center text-zinc-400 text-xs border border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl">
                            Belum ada tugas
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ============================================== -->
            <!-- KOLOM 3: REVIEW (Dot Oranye/Amber)            -->
            <!-- ============================================== -->
            <div 
                @dragover.prevent="dragOverColumn = 'review'"
                @dragleave="dragOverColumn = null"
                @drop="moveTask(draggedTaskId, 'review'); dragOverColumn = null"
                :class="dragOverColumn === 'review' ? 'ring-2 ring-indigo-500 bg-indigo-50/20 dark:bg-indigo-950/20' : ''"
                class="rounded-2xl bg-zinc-50/70 dark:bg-zinc-900/40 border border-zinc-200/80 dark:border-zinc-800 p-3.5 transition-all duration-150"
            >
                <!-- Header Kolom -->
                <div class="flex items-center justify-between pb-3 px-1">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-zinc-100">
                            Review
                        </h3>
                        <span class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500">
                            ({{ $reviewTasks->count() }})
                        </span>
                    </div>
                    <div class="flex items-center space-x-1 text-zinc-400">
                        @can('create', App\Models\Task::class)
                            <a href="{{ route('tasks.create') }}" class="p-1 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors" title="Tambah Tugas Baru">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                        @endcan
                        <button type="button" class="p-1 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors" title="Opsi Kolom">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- List Card Tugas di Kolom 3 -->
                <div class="space-y-3 min-h-[140px]">
                    @forelse($reviewTasks as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="py-8 text-center text-zinc-400 text-xs border border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl">
                            Belum ada tugas
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ============================================== -->
            <!-- KOLOM 4: SELESAI (Dot Hijau/Emerald)          -->
            <!-- ============================================== -->
            <div 
                @dragover.prevent="dragOverColumn = 'completed'"
                @dragleave="dragOverColumn = null"
                @drop="moveTask(draggedTaskId, 'completed'); dragOverColumn = null"
                :class="dragOverColumn === 'completed' ? 'ring-2 ring-indigo-500 bg-indigo-50/20 dark:bg-indigo-950/20' : ''"
                class="rounded-2xl bg-zinc-50/70 dark:bg-zinc-900/40 border border-zinc-200/80 dark:border-zinc-800 p-3.5 transition-all duration-150"
            >
                <!-- Header Kolom -->
                <div class="flex items-center justify-between pb-3 px-1">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-zinc-100">
                            Selesai
                        </h3>
                        <span class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500">
                            ({{ $completedTasks->count() }})
                        </span>
                    </div>
                    <div class="flex items-center space-x-1 text-zinc-400">
                        @can('create', App\Models\Task::class)
                            <a href="{{ route('tasks.create') }}" class="p-1 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors" title="Tambah Tugas Baru">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                        @endcan
                        <button type="button" class="p-1 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors" title="Opsi Kolom">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- List Card Tugas di Kolom 4 -->
                <div class="space-y-3 min-h-[140px]">
                    @forelse($completedTasks as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="py-8 text-center text-zinc-400 text-xs border border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl">
                            Belum ada tugas selesai
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- 5. LIST VIEW (KETIKA DITOGGLE KE MODE LIST) -->
    <div x-show="viewMode === 'list'" x-cloak class="bg-white dark:bg-[#121215] rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-zinc-50 dark:bg-zinc-900/60 border-b border-zinc-200/80 dark:border-zinc-800 text-zinc-400 dark:text-zinc-500 uppercase font-semibold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Tugas</th>
                        <th class="py-3 px-4">Kategori / Divisi</th>
                        <th class="py-3 px-4">Penanggung Jawab</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Prioritas</th>
                        <th class="py-3 px-4">Deadline</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/80 text-zinc-600 dark:text-zinc-400">
                    @forelse($tasks as $t)
                        <tr 
                            x-show="taskMatchesTab('{{ $t->status->value }}', '{{ $t->priority->value }}')"
                            class="hover:bg-zinc-50/60 dark:hover:bg-zinc-800/30 transition-colors"
                        >
                            <td class="py-3.5 px-4 font-semibold text-zinc-900 dark:text-zinc-100">
                                <a href="{{ route('tasks.show', $t) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors block">
                                    {{ $t->title }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-mono text-[11px] px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">
                                    {{ $t->assignee?->division?->code ?? 'OPS' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-2">
                                    <img src="{{ $t->assignee?->avatarUrl() }}" alt="{{ $t->assignee?->name }}" class="w-5 h-5 rounded-full object-cover">
                                    <span class="truncate max-w-[140px]">{{ $t->assignee?->name ?? 'Belum ada' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $t->status->badgeClasses() }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $t->status->dotColor() }} mr-1"></span>
                                    {{ $t->status->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if(in_array($t->priority->value, ['high', 'urgent'], true))
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                        Tinggi
                                    </span>
                                @elseif($t->priority->value === 'medium')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                                        Sedang
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-50 text-zinc-500 dark:bg-zinc-850 dark:text-zinc-500 border border-zinc-200/60 dark:border-zinc-800">
                                        Rendah
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px]">
                                <span class="text-zinc-500 dark:text-zinc-400">{{ $t->deadline->translatedFormat('d M Y') }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('tasks.show', $t) }}" class="font-bold text-zinc-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-zinc-400">Tidak ada tugas yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
