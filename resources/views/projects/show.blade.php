@extends('layouts.app')

@section('title', $project->name . ' - Detail Project')

@section('content')
<div 
    x-data="{
        activeTab: 'milestones',
        newMilestoneModalOpen: false,
        newTaskModalOpen: false,
        assignMemberModalOpen: false,
        selectedMilestoneId: null,
        editMilestoneModalOpen: false,
        editingMilestone: { id: null, title: '', description: '', status: 'pending', due_date: '' },

        openNewTaskModal(milestoneId = null) {
            this.selectedMilestoneId = milestoneId;
            this.newTaskModalOpen = true;
        },

        openEditMilestone(m) {
            this.editingMilestone = {
                id: m.id,
                title: m.title,
                description: m.description || '',
                status: m.status,
                due_date: m.due_date ? m.due_date.substring(0, 10) : ''
            };
            this.editMilestoneModalOpen = true;
        }
    }"
    class="space-y-6 max-w-7xl mx-auto"
>
    <!-- 1. BREADCRUMB & BACK BUTTON -->
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center space-x-2 text-[11px] font-semibold tracking-wider text-zinc-400 dark:text-zinc-500 uppercase leading-none">
            <a href="{{ route('projects.index') }}" class="hover:text-zinc-900 dark:hover:text-zinc-200 transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Projects</span>
            </a>
            <span class="text-zinc-300 dark:text-zinc-600">&rsaquo;</span>
            <span class="font-mono text-zinc-600 dark:text-zinc-300">{{ $project->code }}</span>
            <span class="text-zinc-300 dark:text-zinc-600">&rsaquo;</span>
            <span class="truncate max-w-[200px] text-zinc-800 dark:text-zinc-200">{{ $project->name }}</span>
        </div>

        <a 
            href="{{ route('projects.index') }}"
            class="inline-flex items-center gap-1 text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors"
        >
            &larr; Kembali ke Daftar Project
        </a>
    </div>

    <!-- 2. PROJECT HERO CARD -->
    <div class="rounded-3xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-zinc-200/80 dark:border-zinc-800 p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-zinc-100 dark:border-zinc-800/80">
            <!-- Left Info -->
            <div class="space-y-2 max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-mono text-xs font-bold text-zinc-500 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-800 px-2.5 py-0.5 rounded-full border border-zinc-200/60 dark:border-zinc-700/60">
                        {{ $project->code }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $project->status->badgeClasses() }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $project->status->dotColor() }} inline-block mr-1"></span>
                        {{ $project->status->label() }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                        {{ $project->category->label() }}
                    </span>
                    @if($project->priority)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                            Prioritas: {{ ucfirst($project->priority) }}
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white tracking-tight">
                    {{ $project->name }}
                </h1>

                @if($project->description)
                    <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed pt-1">
                        {{ $project->description }}
                    </p>
                @endif
            </div>

            <!-- Right Actions -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                @if($canManageMilestones)
                    <button 
                        type="button" 
                        @click="newMilestoneModalOpen = true"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-zinc-700 dark:text-zinc-200 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-750 transition-all shadow-2xs cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+ Milestone Baru</span>
                    </button>
                @endif

                @if($canManageMilestones)
                    <button 
                        type="button" 
                        @click="openNewTaskModal(null)"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 transition-all shadow-sm cursor-pointer"
                    >
                        <span class="text-sm leading-none">+</span>
                        <span>Buat Tugas Baru</span>
                    </button>
                @endif

                @if($canAssignMember)
                    <button 
                        type="button" 
                        @click="assignMemberModalOpen = true"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50/70 hover:bg-indigo-50 dark:bg-indigo-950/40 dark:hover:bg-indigo-950/60 border border-indigo-200/80 dark:border-indigo-900/50 transition-all cursor-pointer"
                        title="Tugaskan Anggota Baru ke Project"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>Tugaskan Tim</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Project Meta Strip: Progress, Timeline, Budget, Team -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-5 text-xs">
            <!-- Progress -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400">
                    <span class="font-medium text-[11px] uppercase tracking-wider">Progress</span>
                    <span class="font-bold text-zinc-900 dark:text-white font-mono">{{ $project->progress }}%</span>
                </div>
                <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-indigo-500 h-2 rounded-full transition-all duration-500" style="width: {{ $project->progress }}%"></div>
                </div>
                <p class="text-[11px] text-zinc-400">{{ $project->tasks->where('status', 'completed')->count() }} dari {{ $project->tasks->count() }} tugas selesai</p>
            </div>

            <!-- Deadline -->
            <div class="space-y-1">
                <span class="font-medium text-[11px] text-zinc-500 dark:text-zinc-400 uppercase tracking-wider block">Target Selesai</span>
                <p class="font-bold text-zinc-900 dark:text-white text-sm">
                    {{ $project->deadline ? $project->deadline->translatedFormat('d F Y') : 'Fleksibel' }}
                </p>
                <p class="text-[11px] text-zinc-400">
                    {{ $project->deadline ? $project->deadline->diffForHumans() : '-' }}
                </p>
            </div>

            <!-- Budget -->
            <div class="space-y-1">
                <span class="font-medium text-[11px] text-zinc-500 dark:text-zinc-400 uppercase tracking-wider block">Estimasi Anggaran</span>
                <p class="font-bold text-zinc-900 dark:text-white text-sm">
                    {{ $project->budget ?: 'Internal HQ' }}
                </p>
                <p class="text-[11px] text-zinc-400">Pemberi Kerja: {{ $project->creator?->name ?? 'Management' }}</p>
            </div>

            <!-- Assigned Members Avatars -->
            <div class="space-y-1">
                <span class="font-medium text-[11px] text-zinc-500 dark:text-zinc-400 uppercase tracking-wider block">Tim Proyek ({{ $project->members->count() }})</span>
                <div class="flex items-center -space-x-1.5 pt-0.5">
                    @forelse($project->members->take(5) as $member)
                        <img 
                            src="{{ $member->avatarUrl() }}" 
                            alt="{{ $member->name }}" 
                            title="{{ $member->name }} - {{ $member->pivot->role ?? $member->roleDisplayName() }}"
                            class="w-7 h-7 rounded-full object-cover ring-2 ring-white dark:ring-zinc-900"
                        >
                    @empty
                        <span class="text-xs text-zinc-400 italic">Belum ada anggota</span>
                    @endforelse
                    @if($project->members->count() > 5)
                        <span class="w-7 h-7 rounded-full bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200 text-[10px] font-bold flex items-center justify-center ring-2 ring-white dark:ring-zinc-900">
                            +{{ $project->members->count() - 5 }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if(!empty($project->tech_stack))
            <div class="flex flex-wrap items-center gap-1.5 pt-4 mt-4 border-t border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 mr-1">Teknologi:</span>
                @foreach($project->tech_stack as $stack)
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border border-zinc-200/80 dark:border-zinc-700/80">
                        {{ $stack }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 3. NAVIGATION TABS: Milestones, Semua Tugas, Anggota Tim -->
    <div class="flex items-center gap-3 border-b border-zinc-200 dark:border-zinc-800 pb-0 text-xs font-semibold">
        <button 
            type="button" 
            @click="activeTab = 'milestones'"
            :class="activeTab === 'milestones' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white font-bold' : 'border-transparent text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200'"
            class="py-3 px-1 border-b-2 transition-colors flex items-center gap-1.5 cursor-pointer"
        >
            <span>Milestones & Roadmap</span>
            <span class="text-[11px] px-1.5 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-500 font-normal">({{ $project->milestones->count() }})</span>
        </button>

        <button 
            type="button" 
            @click="activeTab = 'tasks'"
            :class="activeTab === 'tasks' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white font-bold' : 'border-transparent text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200'"
            class="py-3 px-1 border-b-2 transition-colors flex items-center gap-1.5 cursor-pointer"
        >
            <span>Daftar Tugas Proyek</span>
            <span class="text-[11px] px-1.5 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-500 font-normal">({{ $project->tasks->count() }})</span>
        </button>

        <button 
            type="button" 
            @click="activeTab = 'members'"
            :class="activeTab === 'members' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white font-bold' : 'border-transparent text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200'"
            class="py-3 px-1 border-b-2 transition-colors flex items-center gap-1.5 cursor-pointer"
        >
            <span>Anggota Tim & Penugasan</span>
            <span class="text-[11px] px-1.5 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-500 font-normal">({{ $project->members->count() }})</span>
        </button>
    </div>

    <!-- 4. TAB CONTENT: MILESTONES (BREAKDOWN) -->
    <div x-show="activeTab === 'milestones'" class="space-y-6">
        @if($project->milestones->isEmpty())
            <div class="rounded-3xl bg-white/70 dark:bg-zinc-900/60 border border-dashed border-zinc-200 dark:border-zinc-800 p-12 text-center">
                <div class="w-12 h-12 rounded-2xl bg-zinc-100 dark:bg-zinc-800 text-zinc-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Belum Ada Milestone</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 max-w-sm mx-auto">
                    Team Lead dapat memecah project ini ke dalam beberapa milestone terstruktur sebelum menugaskan task ke Staff.
                </p>
                @if($canManageMilestones)
                    <button 
                        type="button" 
                        @click="newMilestoneModalOpen = true"
                        class="mt-4 inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 cursor-pointer"
                    >
                        <span>+ Buat Milestone Pertama</span>
                    </button>
                @endif
            </div>
        @else
            <div class="space-y-5">
                @foreach($project->milestones as $milestone)
                    @php
                        $mTasks = $milestone->tasks;
                        $mCompleted = $mTasks->where('status', 'completed')->count();
                        $mTotal = $mTasks->count();
                        $mPercent = $mTotal > 0 ? (int) round(($mCompleted / $mTotal) * 100) : ($milestone->status === 'completed' ? 100 : 0);
                    @endphp

                    <div class="rounded-3xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800 overflow-hidden shadow-2xs transition-all">
                        <!-- Milestone Header -->
                        <div class="p-5 sm:p-6 bg-zinc-50/50 dark:bg-zinc-850/50 border-b border-zinc-100 dark:border-zinc-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-zinc-200 dark:bg-zinc-700 text-[11px] font-bold text-zinc-700 dark:text-zinc-200 flex items-center justify-center shrink-0">
                                        {{ $loop->iteration }}
                                    </span>
                                    <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100 truncate">
                                        {{ $milestone->title }}
                                    </h3>

                                    @if($milestone->status === 'completed')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                            Selesai
                                        </span>
                                    @elseif($milestone->status === 'in_progress')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                            Sedang Berjalan
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                                            Belum Dimulai
                                        </span>
                                    @endif
                                </div>

                                @if($milestone->description)
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 pl-8">
                                        {{ $milestone->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- Milestone Progress & Actions -->
                            <div class="flex items-center gap-4 shrink-0 pl-8 sm:pl-0">
                                <div class="text-right">
                                    <div class="flex items-center gap-2 justify-end text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                        <span class="text-[11px] text-zinc-400 font-mono">{{ $mCompleted }}/{{ $mTotal }} Tugas</span>
                                        <span class="font-bold text-zinc-900 dark:text-white">{{ $mPercent }}%</span>
                                    </div>
                                    <div class="w-32 bg-zinc-200 dark:bg-zinc-700 h-1.5 rounded-full overflow-hidden mt-1">
                                        <div class="bg-indigo-600 dark:bg-indigo-500 h-1.5 rounded-full" style="width: {{ $mPercent }}%"></div>
                                    </div>
                                    @if($milestone->due_date)
                                        <span class="text-[10px] text-zinc-400 block mt-1">Target: {{ $milestone->due_date->translatedFormat('d M Y') }}</span>
                                    @endif
                                </div>

                                @if($canManageMilestones)
                                    <div class="flex items-center gap-1.5 pl-3 border-l border-zinc-200 dark:border-zinc-700">
                                        <button 
                                            type="button" 
                                            @click="openNewTaskModal({{ $milestone->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 cursor-pointer transition-colors"
                                            title="Tambah tugas ke milestone ini"
                                        >
                                            <span class="text-xs">+</span>
                                            <span>Tugas</span>
                                        </button>

                                        <button 
                                            type="button"
                                            @click="openEditMilestone({{ Js::from($milestone) }})"
                                            class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 cursor-pointer"
                                            title="Edit Milestone"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Milestone Tasks List -->
                        <div class="p-4 sm:p-5">
                            @if($mTasks->isEmpty())
                                <p class="text-xs text-zinc-400 text-center py-4 italic">Belum ada tugas di milestone ini. Silakan tambahkan tugas untuk menugaskan Staff.</p>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($mTasks as $task)
                                        <div class="p-3.5 rounded-2xl bg-white dark:bg-zinc-850 border border-zinc-200/70 dark:border-zinc-800 flex items-start justify-between gap-3 shadow-2xs hover:border-zinc-300 dark:hover:border-zinc-700 transition-all">
                                            <div class="space-y-1.5 min-w-0 flex-1">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $task->status->badgeClasses() }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $task->status->dotColor() }} inline-block mr-1"></span>
                                                        {{ $task->status->label() }}
                                                    </span>

                                                    @if(in_array($task->priority->value, ['high', 'urgent'], true))
                                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                                            {{ ucfirst($task->priority->value) }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <a href="{{ route('tasks.show', $task) }}" class="font-bold text-xs text-zinc-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors line-clamp-2 block leading-snug">
                                                    {{ $task->title }}
                                                </a>

                                                <div class="flex items-center gap-2 text-[11px] text-zinc-500 dark:text-zinc-400 pt-1">
                                                    <div class="flex items-center gap-1.5 min-w-0">
                                                        <img src="{{ $task->assignee?->avatarUrl() }}" alt="{{ $task->assignee?->name }}" class="w-4 h-4 rounded-full object-cover">
                                                        <span class="truncate max-w-[110px] font-medium text-zinc-700 dark:text-zinc-300">{{ $task->assignee?->name ?? 'Belum ada' }}</span>
                                                    </div>
                                                    <span>&bull;</span>
                                                    <span class="text-zinc-400">{{ $task->deadline->translatedFormat('d M') }}</span>
                                                </div>
                                            </div>

                                            <a 
                                                href="{{ route('tasks.show', $task) }}" 
                                                class="text-xs font-bold text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 p-1 shrink-0"
                                                title="Lihat Detail Tugas"
                                            >
                                                &rarr;
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 5. TAB CONTENT: SEMUA TUGAS PROYEK -->
    <div x-show="activeTab === 'tasks'" class="space-y-4">
        <div class="rounded-3xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-850/80 border-b border-zinc-200/80 dark:border-zinc-800 text-zinc-400 uppercase font-semibold text-[10px] tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Tugas</th>
                            <th class="py-3 px-4">Milestone</th>
                            <th class="py-3 px-4">Ditugaskan Kepada</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Prioritas</th>
                            <th class="py-3 px-4">Deadline</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/80 text-zinc-600 dark:text-zinc-300">
                        @forelse($project->tasks as $task)
                            <tr class="hover:bg-zinc-50/60 dark:hover:bg-zinc-800/30 transition-colors">
                                <td class="py-3.5 px-4 font-semibold text-zinc-900 dark:text-zinc-100">
                                    <a href="{{ route('tasks.show', $task) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors block">
                                        {{ $task->title }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-[11px] font-medium text-zinc-500 dark:text-zinc-400">
                                        {{ $task->milestone?->title ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center space-x-2">
                                        <img src="{{ $task->assignee?->avatarUrl() }}" alt="{{ $task->assignee?->name }}" class="w-5 h-5 rounded-full object-cover">
                                        <span class="truncate max-w-[130px]">{{ $task->assignee?->name ?? 'Belum ada' }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $task->status->badgeClasses() }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $task->status->dotColor() }} mr-1"></span>
                                        {{ $task->status->label() }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if(in_array($task->priority->value, ['high', 'urgent'], true))
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                            {{ ucfirst($task->priority->value) }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                                            {{ ucfirst($task->priority->value) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-zinc-500 dark:text-zinc-400">
                                    {{ $task->deadline->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('tasks.show', $task) }}" class="font-bold text-zinc-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        Detail &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-zinc-400">Belum ada tugas di project ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 6. TAB CONTENT: ANGGOTA TIM -->
    <div x-show="activeTab === 'members'" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($project->members as $member)
                <div class="rounded-2xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800 p-4 shadow-2xs flex items-center justify-between gap-3">
                    <div class="flex items-center space-x-3 min-w-0">
                        <img src="{{ $member->avatarUrl() }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-zinc-100 dark:ring-zinc-800">
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate">{{ $member->name }}</h4>
                            <span class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 block truncate">
                                {{ $member->pivot->role ?? $member->roleDisplayName() }}
                            </span>
                            <span class="text-[10px] text-zinc-400 block truncate">{{ $member->division?->name ?? 'Head Office' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 shrink-0">
                        @if($member->telegramUrl())
                            <a 
                                href="{{ $member->telegramUrl() }}" 
                                target="_blank"
                                class="p-1.5 rounded-full text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/40 transition-colors"
                                title="Chat via Telegram"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                                </svg>
                            </a>
                        @endif

                        @if($canAssignMember && $member->id !== $project->created_by && $member->id !== auth()->id())
                            <form action="{{ route('projects.members.remove', [$project, $member]) }}" method="POST" onsubmit="return confirm('Keluarkan anggota ini dari project?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-zinc-400 hover:text-rose-500 rounded-lg transition-colors cursor-pointer" title="Keluarkan Anggota">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-xs text-zinc-400">
                    Belum ada anggota yang ditugaskan ke project ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 1: BUAT MILESTONE BARU                             -->
    <!-- ======================================================== -->
    <div x-show="newMilestoneModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="newMilestoneModalOpen = false" class="fixed inset-0 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs"></div>

        <div class="relative bg-white dark:bg-zinc-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-zinc-200 dark:border-zinc-800 z-10 text-zinc-900 dark:text-zinc-100">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Tambah Milestone Baru</h3>
                <button @click="newMilestoneModalOpen = false" class="text-zinc-400 hover:text-zinc-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('milestones.store', $project) }}" method="POST" class="mt-4 space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Judul Milestone *</label>
                    <input type="text" name="title" required placeholder="Contoh: Arsitektur & Integrasi Sandbox" class="w-full px-3.5 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:focus:ring-white">
                </div>

                <div>
                    <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Deskripsi / Target Milestone</label>
                    <textarea name="description" rows="2" placeholder="Jelaskan output yang diharapkan dari milestone ini..." class="w-full px-3.5 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:focus:ring-white"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Status Awal</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                            <option value="pending">Belum Dimulai</option>
                            <option value="in_progress">Sedang Berjalan</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Target Tanggal</label>
                        <input type="date" name="due_date" class="w-full px-3 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2">
                    <button type="button" @click="newMilestoneModalOpen = false" class="px-3.5 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-semibold cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-950 font-bold cursor-pointer">Simpan Milestone</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 2: EDIT MILESTONE                                  -->
    <!-- ======================================================== -->
    <div x-show="editMilestoneModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="editMilestoneModalOpen = false" class="fixed inset-0 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs"></div>

        <div class="relative bg-white dark:bg-zinc-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-zinc-200 dark:border-zinc-800 z-10 text-zinc-900 dark:text-zinc-100">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Edit Milestone</h3>
                <button @click="editMilestoneModalOpen = false" class="text-zinc-400 hover:text-zinc-600 text-lg">&times;</button>
            </div>

            <form :action="'/milestones/' + editingMilestone.id" method="POST" class="mt-4 space-y-3.5 text-xs">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Judul Milestone *</label>
                    <input type="text" name="title" x-model="editingMilestone.title" required class="w-full px-3.5 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                </div>

                <div>
                    <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Deskripsi</label>
                    <textarea name="description" x-model="editingMilestone.description" rows="2" class="w-full px-3.5 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Status</label>
                        <select name="status" x-model="editingMilestone.status" class="w-full px-3 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                            <option value="pending">Belum Dimulai</option>
                            <option value="in_progress">Sedang Berjalan</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Target Tanggal</label>
                        <input type="date" name="due_date" x-model="editingMilestone.due_date" class="w-full px-3 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-between">
                    <button 
                        type="button" 
                        @click="if(confirm('Hapus milestone ini?')) { document.getElementById('delete-milestone-form').submit(); }"
                        class="text-xs text-rose-500 hover:text-rose-600 font-semibold cursor-pointer"
                    >
                        Hapus Milestone
                    </button>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="editMilestoneModalOpen = false" class="px-3.5 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-semibold cursor-pointer">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-950 font-bold cursor-pointer">Update</button>
                    </div>
                </div>
            </form>

            <form :action="'/milestones/' + editingMilestone.id" id="delete-milestone-form" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 3: BUAT TUGAS BARU DI PROJECT INI                  -->
    <!-- ======================================================== -->
    <div x-show="newTaskModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="newTaskModalOpen = false" class="fixed inset-0 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs"></div>

        <div class="relative bg-white dark:bg-zinc-900 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-zinc-200 dark:border-zinc-800 z-10 text-zinc-900 dark:text-zinc-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                <div>
                    <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Buat Tugas Proyek Baru</h3>
                    <p class="text-[11px] text-zinc-400 mt-0.5">Tugaskan pekerjaan ke anggota tim di project ini</p>
                </div>
                <button @click="newTaskModalOpen = false" class="text-zinc-400 hover:text-zinc-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('tasks.store') }}" method="POST" class="mt-4 space-y-3.5 text-xs">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div>
                    <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Milestone Terkait</label>
                    <select name="milestone_id" x-model="selectedMilestoneId" class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                        <option value="">-- Tanpa Milestone (General Task) --</option>
                        @foreach($project->milestones as $m)
                            <option value="{{ $m->id }}">{{ $m->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Judul Tugas / Pekerjaan *</label>
                    <input type="text" name="title" required placeholder="Contoh: Implementasi webhook callback verifikasi" class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-900">
                </div>

                <div>
                    <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Deskripsi / Acceptance Criteria</label>
                    <textarea name="description" rows="3" placeholder="Instruksi pengerjaan spesifik untuk staff..." class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Tugaskan Kepada (Staff) *</label>
                        <select name="assigned_to" required class="w-full px-3 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                            @foreach($assignableUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->roleDisplayName() }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Prioritas *</label>
                        <select name="priority" required class="w-full px-3 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                            <option value="low">Rendah</option>
                            <option value="medium" selected>Sedang</option>
                            <option value="high">Tinggi</option>
                            <option value="urgent">Mendesak (Urgent)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Batas Waktu (Deadline) *</label>
                    <input type="datetime-local" name="deadline" required value="{{ now()->addDays(2)->format('Y-m-d\TH:i') }}" class="w-full px-3.5 py-2 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-zinc-100 dark:border-zinc-800">
                    <button type="button" @click="newTaskModalOpen = false" class="px-4 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-semibold cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-950 font-bold cursor-pointer">Tugaskan Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 4: TUGASKAN ANGGOTA BARU KE PROJECT                 -->
    <!-- ======================================================== -->
    <div x-show="assignMemberModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="assignMemberModalOpen = false" class="fixed inset-0 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs"></div>

        <div class="relative bg-white dark:bg-zinc-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-zinc-200 dark:border-zinc-800 z-10 text-zinc-900 dark:text-zinc-100">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Tugaskan Anggota ke Project</h3>
                <button @click="assignMemberModalOpen = false" class="text-zinc-400 hover:text-zinc-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('projects.members.assign', $project) }}" method="POST" class="mt-4 space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Pilih Karyawan *</label>
                    <select name="user_id" required class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($availableToAdd as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->roleDisplayName() }} - {{ $u->division?->code ?? 'HQ' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Peran / Tanggung Jawab di Project *</label>
                    <input type="text" name="role" required placeholder="Contoh: Senior Backend Engineer / QA Lead" class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white">
                </div>

                <div class="pt-3 flex items-center justify-end gap-2">
                    <button type="button" @click="assignMemberModalOpen = false" class="px-3.5 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-semibold cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-950 font-bold cursor-pointer">Tambahkan ke Tim</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
