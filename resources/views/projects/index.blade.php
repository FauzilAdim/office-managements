@extends('layouts.app')

@section('title', 'PROJECTS - Manajemen Project & Tim')

@section('content')
<div 
    x-data="{
        projectFilter: 'all',
        projectSearch: '',
        showProjectSearch: false,
        newProjectModalOpen: false,
        selectedProject: null,
        assignMemberModalOpen: false,
        assignTargetProject: null,
        selectedEmployeeForAssign: '',
        assignRole: '',
        assignTask: '',
        projectsList: {{ Js::from($projects) }},
        availableEmployees: {{ Js::from($employees) }},

        filteredProjects() {
            return this.projectsList.filter(prj => {
                // Filter by category / status
                if (this.projectFilter !== 'all') {
                    if (this.projectFilter === 'in_progress' && prj.status !== 'in_progress') return false;
                    if (this.projectFilter === 'review' && prj.status !== 'review') return false;
                    if (this.projectFilter === 'planning' && prj.status !== 'planning') return false;
                    if (this.projectFilter === 'completed' && prj.status !== 'completed') return false;
                    if (this.projectFilter === 'tech' && prj.category !== 'tech') return false;
                    if (this.projectFilter === 'marketing' && prj.category !== 'marketing') return false;
                }

                // Filter by search query (project name, description, tech stack, or employee name)
                if (this.projectSearch.trim() !== '') {
                    const q = this.projectSearch.toLowerCase();
                    const matchName = prj.name.toLowerCase().includes(q);
                    const matchDesc = prj.description.toLowerCase().includes(q);
                    const matchCode = prj.code.toLowerCase().includes(q);
                    const matchTech = prj.tech_stack.some(t => t.toLowerCase().includes(q));
                    const matchMember = prj.members.some(m => m.name.toLowerCase().includes(q) || m.role.toLowerCase().includes(q));
                    return matchName || matchDesc || matchCode || matchTech || matchMember;
                }

                return true;
            });
        },

        openAssignModal(prj) {
            this.assignTargetProject = prj;
            this.selectedEmployeeForAssign = '';
            this.assignRole = 'Core Contributor';
            this.assignTask = 'Pengerjaan modul ' + prj.name;
            this.assignMemberModalOpen = true;
        },

        submitAssignMember() {
            if (!this.selectedEmployeeForAssign) {
                alert('Pilih karyawan terlebih dahulu!');
                return;
            }
            const emp = this.availableEmployees.find(e => e.id == this.selectedEmployeeForAssign);
            if (!emp) return;

            // Check if already in project
            const exists = this.assignTargetProject.members.some(m => m.id == emp.id);
            if (exists) {
                showToast(emp.name + ' sudah terdaftar di project ini');
                this.assignMemberModalOpen = false;
                return;
            }

            this.assignTargetProject.members.push({
                id: emp.id,
                name: emp.name,
                role: this.assignRole || 'Contributor',
                avatar: emp.avatar ? '/storage/' + emp.avatar : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&h=256&q=80',
                task: this.assignTask || 'Pengerjaan fitur utama',
                is_online: true
            });

            showToast(emp.name + ' berhasil ditugaskan ke ' + this.assignTargetProject.name);
            this.assignMemberModalOpen = false;
        }
    }" 
    class="w-full pb-16 px-4 sm:px-6 lg:px-8 max-w-[1500px] mx-auto"
>
    <!-- Top Action & Title Bar (Consistent WORKSPACE Style) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-zinc-100 uppercase">PROJECTS</h1>
            
            @if($canCreate)
                <!-- + New Project Button -->
                <a 
                    href="{{ route('projects.create') }}" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 shadow-sm transition-all hover:scale-105 active:scale-95 cursor-pointer ml-1"
                >
                    <span class="text-sm leading-none">+</span>
                    <span>New Project</span>
                </a>
            @endif
        </div>

        <!-- Project Stat Counters -->
        <div class="flex items-center gap-4 flex-wrap">
            <!-- Total Projects -->
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black text-slate-900 dark:text-zinc-100" x-text="projectsList.length"></span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold text-white bg-zinc-900 dark:bg-zinc-800">Projects</span>
            </div>

            <div class="h-5 w-px bg-slate-300 dark:bg-zinc-700"></div>

            <!-- In Progress -->
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black text-slate-900 dark:text-zinc-100" x-text="projectsList.filter(p => p.status === 'in_progress').length"></span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold text-white bg-emerald-600">Active</span>
            </div>

            <!-- In Review -->
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black text-slate-900 dark:text-zinc-100" x-text="projectsList.filter(p => p.status === 'review').length"></span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold text-white bg-blue-600">Review</span>
            </div>

            <!-- Completed -->
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black text-slate-900 dark:text-zinc-100" x-text="projectsList.filter(p => p.status === 'completed').length"></span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold text-white bg-slate-600 dark:bg-zinc-600">Done</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar (Consistent Frosted Glass Pill Bar with Permanent Search Input) -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-7">
        <!-- Left Filter Pills -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Permanent Search Bar with Icon on the Right -->
            <div class="relative flex items-center">
                <input 
                    type="text" 
                    x-model="projectSearch"
                    placeholder="Cari project, tim, tech stack..." 
                    class="h-10 pl-4 pr-10 text-xs rounded-full bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border border-white/80 dark:border-zinc-800 text-slate-900 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-600 focus:ring-1 focus:ring-zinc-900 dark:focus:ring-zinc-600 w-56 sm:w-64 shadow-xs transition-colors"
                >
                <button 
                    type="button" 
                    class="absolute right-1.5 w-7 h-7 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-zinc-100 transition-colors cursor-pointer"
                    title="Cari"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <!-- Reset Filter Button -->
            <button 
                type="button" 
                @click="projectFilter = 'all'; projectSearch = ''; showToast('Filter direset')"
                class="w-10 h-10 rounded-full bg-white/80 hover:bg-white dark:bg-zinc-900/80 dark:hover:bg-zinc-800 backdrop-blur-md border border-white/80 dark:border-zinc-800 shadow-xs flex items-center justify-center text-slate-500 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-zinc-100 transition-colors cursor-pointer shrink-0"
                title="Reset Filter"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
            </button>

            <!-- Filter: Semua -->
            <button 
                type="button" 
                @click="projectFilter = 'all'"
                :class="projectFilter === 'all' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 font-semibold shadow-sm' : 'bg-white/75 hover:bg-white dark:bg-zinc-900/70 dark:hover:bg-zinc-900 text-slate-600 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-zinc-100 border border-white/80 dark:border-zinc-800 backdrop-blur-md shadow-xs'"
                class="px-4 py-2 rounded-full text-xs font-medium transition-all cursor-pointer flex items-center gap-1"
            >
                <span>Semua Project</span>
                <span class="text-[11px] opacity-80" x-text="'(' + projectsList.length + ')'"></span>
            </button>

            <!-- Filter: In Progress -->
            <button 
                type="button" 
                @click="projectFilter = 'in_progress'"
                :class="projectFilter === 'in_progress' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 font-semibold shadow-sm' : 'bg-white/75 hover:bg-white dark:bg-zinc-900/70 dark:hover:bg-zinc-900 text-slate-600 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-zinc-100 border border-white/80 dark:border-zinc-800 backdrop-blur-md shadow-xs'"
                class="px-4 py-2 rounded-full text-xs font-medium flex items-center gap-1.5 transition-all cursor-pointer"
            >
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>In Progress</span>
            </button>

            <!-- Filter: In Review -->
            <button 
                type="button" 
                @click="projectFilter = 'review'"
                :class="projectFilter === 'review' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 font-semibold shadow-sm' : 'bg-white/75 hover:bg-white dark:bg-zinc-900/70 dark:hover:bg-zinc-900 text-slate-600 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-zinc-100 border border-white/80 dark:border-zinc-800 backdrop-blur-md shadow-xs'"
                class="px-4 py-2 rounded-full text-xs font-medium flex items-center gap-1.5 transition-all cursor-pointer"
            >
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                <span>Review</span>
            </button>

            <!-- Filter: Planning -->
            <button 
                type="button" 
                @click="projectFilter = 'planning'"
                :class="projectFilter === 'planning' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 font-semibold shadow-sm' : 'bg-white/75 hover:bg-white dark:bg-zinc-900/70 dark:hover:bg-zinc-900 text-slate-600 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-zinc-100 border border-white/80 dark:border-zinc-800 backdrop-blur-md shadow-xs'"
                class="px-4 py-2 rounded-full text-xs font-medium flex items-center gap-1.5 transition-all cursor-pointer"
            >
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span>Planning</span>
            </button>

            <!-- Filter: Completed -->
            <button 
                type="button" 
                @click="projectFilter = 'completed'"
                :class="projectFilter === 'completed' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 font-semibold shadow-sm' : 'bg-white/75 hover:bg-white dark:bg-zinc-900/70 dark:hover:bg-zinc-900 text-slate-600 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-zinc-100 border border-white/80 dark:border-zinc-800 backdrop-blur-md shadow-xs'"
                class="px-4 py-2 rounded-full text-xs font-medium transition-all cursor-pointer"
            >
                Completed
            </button>
        </div>

        <!-- Right Quick Info -->
        <div class="hidden lg:flex items-center gap-2 text-xs text-slate-500 dark:text-zinc-400 font-medium">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Semua tim terhubung realtime</span>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- PROJECT CARDS GRID (Frosted Glassmorphism + Team Members) -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="prj in filteredProjects()" :key="prj.id">
            <div class="bg-white/80 hover:bg-white/90 dark:bg-zinc-900/80 dark:hover:bg-zinc-900/95 backdrop-blur-md border border-white/80 hover:border-white dark:border-zinc-800/80 dark:hover:border-zinc-700 rounded-3xl p-5 sm:p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.3)] hover:shadow-xl transition-all duration-200 hover:-translate-y-1 flex flex-col justify-between group">
                <div>
                    <!-- Card Top Header: Code, Priority & Status Badges -->
                    <div class="flex items-center justify-between gap-2 mb-3.5">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-[11px] font-bold text-slate-500 dark:text-zinc-400 bg-white/90 dark:bg-zinc-800/90 border border-slate-200/60 dark:border-zinc-700/60 px-2.5 py-0.5 rounded-full shadow-2xs" x-text="prj.code"></span>
                            
                            <span 
                                class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full border"
                                :class="{
                                    'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-900/40': prj.priority === 'Urgent',
                                    'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900/40': prj.priority === 'High',
                                    'bg-slate-50 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300 border-slate-200 dark:border-zinc-700': prj.priority === 'Normal'
                                }"
                                x-text="prj.priority"
                            ></span>
                        </div>

                        <!-- Status Pill with Pulsing Dot -->
                        <div class="flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border border-slate-200/60 dark:border-zinc-700/60 bg-white/80 dark:bg-zinc-800/80 shadow-2xs">
                            <span 
                                class="w-2 h-2 rounded-full"
                                :class="{
                                    'bg-emerald-500 animate-pulse': prj.status === 'in_progress',
                                    'bg-blue-500 animate-pulse': prj.status === 'review',
                                    'bg-amber-500': prj.status === 'planning',
                                    'bg-slate-400': prj.status === 'completed'
                                }"
                            ></span>
                            <span class="text-slate-800 dark:text-zinc-200 text-[11px]" x-text="prj.status_label"></span>
                        </div>
                    </div>

                    <!-- Project Title & Division -->
                    <div class="mb-3">
                        <a :href="'/projects/' + prj.id" class="text-base sm:text-lg font-bold text-slate-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400 leading-snug tracking-tight block transition-colors" x-text="prj.name"></a>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span x-text="prj.division ? prj.division : (prj.category ? prj.category.toUpperCase() : 'HQ')"></span>
                        </p>
                    </div>

                    <!-- Project Description -->
                    <p class="text-xs text-slate-600 dark:text-zinc-300 leading-relaxed mb-4 line-clamp-2" x-text="prj.description"></p>

                    <!-- Progress Bar & Milestone Status -->
                    <div class="p-3.5 rounded-2xl bg-white/60 dark:bg-zinc-950/60 border border-slate-200/60 dark:border-zinc-800/80 shadow-xs mb-4.5">
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-zinc-300">Progres Keseluruhan</span>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] text-slate-500 dark:text-zinc-400 font-mono" x-text="prj.tasks_completed + '/' + prj.tasks_total + ' Tugas'"></span>
                                <span class="font-extrabold text-slate-900 dark:text-zinc-100 text-xs" x-text="prj.progress + '%'"></span>
                            </div>
                        </div>
                        
                        <!-- Progress Track -->
                        <div class="w-full bg-slate-200/80 dark:bg-zinc-800 rounded-full h-2 overflow-hidden">
                            <div 
                                class="h-2 rounded-full transition-all duration-500"
                                :class="{
                                    'bg-emerald-500': prj.status === 'in_progress',
                                    'bg-blue-600': prj.status === 'review',
                                    'bg-amber-500': prj.status === 'planning',
                                    'bg-slate-700': prj.status === 'completed'
                                }"
                                :style="'width: ' + prj.progress + '%'"
                            ></div>
                        </div>

                        <!-- Timeline & Budget Details -->
                        <div class="flex items-center justify-between mt-2.5 pt-2 border-t border-slate-200/40 dark:border-zinc-800/60 text-[11px] text-slate-500 dark:text-zinc-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span x-text="'Deadline: ' + prj.deadline"></span>
                            </span>
                            <span class="font-semibold text-slate-700 dark:text-zinc-300" x-text="prj.days_left"></span>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TIM YANG TERLIBAT / WHO IS IN THIS PROJECT    -->
                    <!-- ============================================== -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2.5">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="text-xs font-bold text-slate-900 dark:text-zinc-100 tracking-tight">Personil Terlibat</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-slate-200/80 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300" x-text="prj.members.length"></span>
                            </div>

                            <!-- + Assign Member Button -->
                            <button 
                                type="button" 
                                @click="openAssignModal(prj)"
                                class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 flex items-center gap-1 cursor-pointer transition-colors"
                                title="Tugaskan Karyawan ke Project Ini"
                            >
                                <span>+ Tugaskan</span>
                            </button>
                        </div>

                        <!-- Stack of Member Cards / Mini Tiles -->
                        <div class="space-y-2">
                            <template x-for="m in prj.members.slice(0, 3)" :key="m.id">
                                <div class="flex items-center justify-between p-2 rounded-xl bg-white/70 hover:bg-white dark:bg-zinc-800/60 dark:hover:bg-zinc-800 border border-slate-200/60 dark:border-zinc-700/60 shadow-2xs transition-colors">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <!-- Avatar with Online Dot -->
                                        <div class="relative shrink-0">
                                            <img :src="m.avatar_url || m.avatar" :alt="m.name" class="w-8 h-8 rounded-full object-cover ring-1 ring-white/90 dark:ring-zinc-700">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-zinc-100 truncate leading-snug" x-text="m.name"></h4>
                                            <p class="text-[10px] text-slate-500 dark:text-zinc-400 truncate" x-text="m.pivot ? m.pivot.role : 'Member'"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- More Members Overflow Badge -->
                            <template x-if="prj.members.length > 3">
                                <div class="flex items-center justify-between px-2 pt-1 text-[11px] text-slate-500 dark:text-zinc-400">
                                    <span x-text="'+ ' + (prj.members.length - 3) + ' anggota lainnya'"></span>
                                    <button 
                                        type="button" 
                                        @click="selectedProject = prj"
                                        class="font-semibold text-zinc-800 dark:text-zinc-200 hover:underline cursor-pointer"
                                    >
                                        Lihat semua &rarr;
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Tech Stack Tags -->
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        <template x-for="tech in prj.tech_stack" :key="tech">
                            <span class="text-[10px] font-medium px-2 py-0.5 rounded bg-white/80 dark:bg-zinc-800/80 text-slate-600 dark:text-zinc-300 border border-slate-200/60 dark:border-zinc-700/60 shadow-2xs" x-text="tech"></span>
                        </template>
                    </div>
                </div>

                <!-- Card Bottom Footer: Action Button -->
                <div class="pt-3.5 border-t border-slate-200/50 dark:border-zinc-800/60 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400 dark:text-zinc-500 font-medium" x-text="'Budget: ' + prj.budget"></span>

                    <!-- Detail Project Page Link -->
                    <a 
                        :href="'/projects/' + prj.id"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 text-white shadow-xs transition-all hover:scale-105 active:scale-95 cursor-pointer"
                    >
                        <span>Detail Project</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </template>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 1: DETAIL PROJECT & FULL TEAM PERSONNEL DIRECTORY   -->
    <!-- ======================================================== -->
    <div x-show="selectedProject" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div @click="selectedProject = null" class="fixed inset-0 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs"></div>

        <!-- Modal Box -->
        <div class="relative bg-white/95 dark:bg-zinc-900/95 backdrop-blur-2xl rounded-3xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-white/80 dark:border-zinc-800 z-10 overflow-y-auto max-h-[90vh] text-slate-900 dark:text-zinc-100">
            <template x-if="selectedProject">
                <div>
                    <!-- Header -->
                    <div class="flex items-start justify-between pb-4 border-b border-slate-100 dark:border-zinc-800">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="font-mono text-xs font-bold text-slate-500 dark:text-zinc-400 bg-slate-100 dark:bg-zinc-800 px-2 py-0.5 rounded-full" x-text="selectedProject.code"></span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/40" x-text="selectedProject.status_label"></span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/40" x-text="selectedProject.priority"></span>
                            </div>
                            <h3 class="text-xl font-extrabold text-slate-900 dark:text-zinc-100 tracking-tight" x-text="selectedProject.name"></h3>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5" x-text="selectedProject.division"></p>
                        </div>
                        <button @click="selectedProject = null" class="text-slate-400 hover:text-slate-700 dark:hover:text-zinc-200 text-2xl font-bold cursor-pointer">&times;</button>
                    </div>

                    <!-- Description & Objectives -->
                    <div class="mt-4 p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200/80 dark:border-zinc-700/60">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400 mb-1">Deskripsi & Ruang Lingkup</h4>
                        <p class="text-xs text-slate-700 dark:text-zinc-300 leading-relaxed" x-text="selectedProject.description"></p>
                    </div>

                    <!-- Project Key Metrics -->
                    <div class="grid grid-cols-3 gap-3 mt-4 text-center">
                        <div class="p-3 rounded-2xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 shadow-2xs">
                            <span class="text-[10px] text-slate-400 dark:text-zinc-400 uppercase font-bold block">Progres</span>
                            <span class="text-lg font-black text-slate-900 dark:text-zinc-100" x-text="selectedProject.progress + '%'"></span>
                        </div>
                        <div class="p-3 rounded-2xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 shadow-2xs">
                            <span class="text-[10px] text-slate-400 dark:text-zinc-400 uppercase font-bold block">Tugas Selesai</span>
                            <span class="text-lg font-black text-slate-900 dark:text-zinc-100" x-text="selectedProject.tasks_completed + '/' + selectedProject.tasks_total"></span>
                        </div>
                        <div class="p-3 rounded-2xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 shadow-2xs">
                            <span class="text-[10px] text-slate-400 dark:text-zinc-400 uppercase font-bold block">Tenggat Waktu</span>
                            <span class="text-xs font-extrabold text-slate-900 dark:text-zinc-100 block mt-1" x-text="selectedProject.deadline"></span>
                        </div>
                    </div>

                    <!-- ALL TEAM MEMBERS IN THIS PROJECT -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-extrabold text-slate-900 dark:text-zinc-100 flex items-center gap-2">
                                <span>Daftar Personil Project</span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200" x-text="selectedProject.members.length + ' Orang'"></span>
                            </h4>

                            <button 
                                type="button" 
                                @click="openAssignModal(selectedProject)"
                                class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 cursor-pointer"
                            >
                                + Tambah Personil
                            </button>
                        </div>

                        <!-- Full Members List -->
                        <div class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                            <template x-for="m in selectedProject.members" :key="m.id">
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200/80 dark:border-zinc-700/60">
                                    <div class="flex items-center gap-3">
                                        <div class="relative shrink-0">
                                            <img :src="m.avatar" :alt="m.name" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-zinc-700">
                                            <template x-if="m.is_online">
                                                <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-zinc-800"></span>
                                            </template>
                                        </div>
                                        <div>
                                            <h5 class="text-xs font-bold text-slate-900 dark:text-zinc-100 leading-snug" x-text="m.name"></h5>
                                            <p class="text-[11px] text-slate-500 dark:text-zinc-400" x-text="m.role"></p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <span class="text-xs font-semibold text-slate-800 dark:text-zinc-200 block" x-text="m.task"></span>
                                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold" x-text="m.is_online ? '● Online Aktif' : '○ Offline'"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Tech Stack in Detail -->
                    <div class="mt-5 pt-4 border-t border-slate-100 dark:border-zinc-800">
                        <span class="text-[10px] text-slate-400 dark:text-zinc-400 uppercase font-bold block mb-2">Teknologi & Tools yang Digunakan</span>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="tech in selectedProject.tech_stack" :key="tech">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700" x-text="tech"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-end gap-2.5">
                        <button 
                            type="button" 
                            @click="selectedProject = null" 
                            class="px-4 py-2 rounded-full text-xs font-semibold text-slate-600 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-zinc-100 bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 dark:hover:bg-zinc-700 cursor-pointer"
                        >
                            Tutup
                        </button>
                        <button 
                            type="button" 
                            @click="showToast('Laporan progress project ' + selectedProject.name + ' berhasil diunduh'); selectedProject = null" 
                            class="px-5 py-2 rounded-full text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 shadow-md transition-all active:scale-95 cursor-pointer"
                        >
                            Unduh Ringkasan
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 2: ASSIGN MEMBER (TUGASKAN KARYAWAN KE PROJECT)     -->
    <!-- ======================================================== -->
    <div x-show="assignMemberModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div @click="assignMemberModalOpen = false" class="fixed inset-0 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs"></div>

        <!-- Modal Box -->
        <div class="relative bg-white/95 dark:bg-zinc-900/95 backdrop-blur-2xl rounded-3xl max-w-md w-full p-6 shadow-2xl border border-white/80 dark:border-zinc-800 z-10 text-slate-900 dark:text-zinc-100">
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 dark:border-zinc-800">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-zinc-100">Tugaskan Karyawan</h3>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5" x-text="'Ke: ' + (assignTargetProject ? assignTargetProject.name : '')"></p>
                </div>
                <button @click="assignMemberModalOpen = false" class="text-slate-400 hover:text-slate-700 dark:hover:text-zinc-200 text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="submitAssignMember()" class="mt-4 space-y-3.5">
                <!-- Select Employee -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">Pilih Karyawan</label>
                    <select 
                        x-model="selectedEmployeeForAssign" 
                        required
                        class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                    >
                        <option value="">-- Pilih Anggota Tim --</option>
                        <template x-for="emp in availableEmployees" :key="emp.id">
                            <option :value="emp.id" x-text="emp.name + ' (' + (emp.position || 'Staff') + ')'"></option>
                        </template>
                    </select>
                </div>

                <!-- Role in Project -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">Peran di Project</label>
                    <input 
                        type="text" 
                        x-model="assignRole" 
                        placeholder="Contoh: Lead Frontend Engineer / Tester" 
                        required
                        class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                    >
                </div>

                <!-- Specific Task Assignment -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">Tugas / Tanggung Jawab Spesifik</label>
                    <input 
                        type="text" 
                        x-model="assignTask" 
                        placeholder="Contoh: Pengerjaan webhook callback & testing" 
                        required
                        class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                    >
                </div>

                <div class="pt-3 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-end gap-2">
                    <button 
                        type="button" 
                        @click="assignMemberModalOpen = false" 
                        class="px-4 py-2 rounded-full text-xs font-semibold text-slate-600 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-zinc-100 cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2 rounded-full text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 shadow-md transition-all active:scale-95 cursor-pointer"
                    >
                        Simpan Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 3: BUAT PROJECT BARU (NEW PROJECT)                 -->
    <!-- ======================================================== -->
    <div 
        x-show="newProjectModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-data="{
            newProjName: '',
            newProjDivision: 'Technology & Engineering',
            newProjDesc: '',
            newProjPriority: 'High',
            newProjDeadline: '15.11.2026',
            newProjBudget: 'Rp 150.000.000',
            newProjTech: 'Laravel 11, Tailwind, PostgreSQL',
            submitNewProject() {
                if (!this.newProjName) {
                    alert('Judul project wajib diisi!');
                    return;
                }
                const newId = projectsList.length + 1;
                projectsList.unshift({
                    id: newId,
                    name: this.newProjName,
                    code: 'PRJ-NEW-0' + newId,
                    division: this.newProjDivision,
                    category: 'tech',
                    description: this.newProjDesc || 'Inisiatif project baru untuk optimasi operasional tim.',
                    status: 'in_progress',
                    status_label: 'In Progress',
                    status_color: 'emerald',
                    priority: this.newProjPriority,
                    priority_color: 'rose',
                    progress: 10,
                    deadline: this.newProjDeadline,
                    days_left: '30 Hari',
                    budget: this.newProjBudget,
                    tasks_completed: 1,
                    tasks_total: 10,
                    tech_stack: this.newProjTech.split(',').map(s => s.trim()),
                    members: [
                        {
                            id: 1,
                            name: 'Fauzil Adzim, S.Tr.Kom.',
                            role: 'Tech Lead / Inisiator',
                            avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&h=256&q=80',
                            task: 'Setup repository & kickoff arsitektur',
                            is_online: true
                        }
                    ]
                });
                showToast('Project ' + this.newProjName + ' berhasil dibuat!');
                newProjectModalOpen = false;
                this.newProjName = '';
                this.newProjDesc = '';
            }
        }"
    >
        <div @click="newProjectModalOpen = false" class="fixed inset-0 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs"></div>

        <div class="relative bg-white/95 dark:bg-zinc-900/95 backdrop-blur-2xl rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-white/80 dark:border-zinc-800 z-10 text-slate-900 dark:text-zinc-100">
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 dark:border-zinc-800">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-zinc-100">Buat Project Baru</h3>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Tambahkan inisiatif project ke workspace tim</p>
                </div>
                <button @click="newProjectModalOpen = false" class="text-slate-400 hover:text-slate-700 dark:hover:text-zinc-200 text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="submitNewProject()" class="mt-4 space-y-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">Nama Project</label>
                    <input 
                        type="text" 
                        x-model="newProjName" 
                        placeholder="Contoh: Redesign Customer Portal 2.0" 
                        required
                        class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                    >
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">Divisi Penanggung Jawab</label>
                        <select 
                            x-model="newProjDivision"
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                        >
                            <option value="Technology & Engineering">Technology & Engineering</option>
                            <option value="Product & Marketing">Product & Marketing</option>
                            <option value="Finance & Operations">Finance & Operations</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">Prioritas</label>
                        <select 
                            x-model="newProjPriority"
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                        >
                            <option value="Urgent">🔥 Urgent</option>
                            <option value="High">High</option>
                            <option value="Normal">Normal</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">Deskripsi Singkat</label>
                    <textarea 
                        x-model="newProjDesc" 
                        rows="2"
                        placeholder="Jelaskan tujuan dan output utama project ini..." 
                        class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                    ></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">Tenggat Waktu (Deadline)</label>
                        <input 
                            type="text" 
                            x-model="newProjDeadline" 
                            placeholder="Contoh: 15.11.2026" 
                            class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">Estimasi Budget</label>
                        <input 
                            type="text" 
                            x-model="newProjBudget" 
                            placeholder="Contoh: Rp 150.000.000" 
                            class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">Tech Stack / Tools (Pisahkan dengan koma)</label>
                    <input 
                        type="text" 
                        x-model="newProjTech" 
                        placeholder="Contoh: Laravel 11, Tailwind, PostgreSQL, Docker" 
                        class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs focus:outline-none focus:border-zinc-900 dark:focus:border-zinc-500"
                    >
                </div>

                <div class="pt-3 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-end gap-2">
                    <button 
                        type="button" 
                        @click="newProjectModalOpen = false" 
                        class="px-4 py-2 rounded-full text-xs font-semibold text-slate-600 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-zinc-100 cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2 rounded-full text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 shadow-md transition-all active:scale-95 cursor-pointer"
                    >
                        Buat Project
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
