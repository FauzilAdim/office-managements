@extends('layouts.app')

@section('title', 'Buat Project Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 text-[11px] font-semibold tracking-wider text-zinc-400 dark:text-zinc-500 uppercase leading-none">
        <a href="{{ route('projects.index') }}" class="hover:text-zinc-900 dark:hover:text-zinc-200 transition-colors">Projects</a>
        <span class="text-zinc-300 dark:text-zinc-600">&rsaquo;</span>
        <span class="text-zinc-700 dark:text-zinc-300">Buat Project Baru</span>
    </div>

    <div class="rounded-3xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-zinc-200/80 dark:border-zinc-800 p-6 sm:p-8 shadow-xs">
        <div class="pb-5 border-b border-zinc-100 dark:border-zinc-800">
            <h1 class="text-xl sm:text-2xl font-black text-zinc-900 dark:text-white tracking-tight">
                Inisiasi Project Baru
            </h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                Tentukan deskripsi proyek strategis dan tugaskan Team Lead yang akan mengoordinasikan pengerjaan.
            </p>
        </div>

        <form action="{{ route('projects.store') }}" method="POST" class="mt-6 space-y-4 text-xs">
            @csrf

            <!-- Project Name -->
            <div>
                <label for="name" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                    Nama Project <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    required 
                    value="{{ old('name') }}"
                    placeholder="Contoh: Redesign Customer Portal & Webhook Callback Gateway" 
                    class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:focus:ring-white"
                >
                @error('name')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Code & Category -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="code" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                        Kode Project (Opsional)
                    </label>
                    <input 
                        type="text" 
                        id="code" 
                        name="code" 
                        value="{{ old('code') }}"
                        placeholder="Contoh: PRJ-TECH-04" 
                        class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:focus:ring-white font-mono uppercase"
                    >
                    <p class="text-[10px] text-zinc-400 mt-1">Kosongkan jika ingin dibuat otomatis.</p>
                </div>

                <div>
                    <label for="category" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                        Kategori Divisi <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="category" 
                        name="category" 
                        required 
                        class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-900"
                    >
                        @foreach($categories as $cat)
                            <option value="{{ $cat->value }}" {{ old('category') === $cat->value ? 'selected' : '' }}>
                                {{ $cat->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                    Deskripsi & Sasaran Project
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="3" 
                    placeholder="Uraikan ruang lingkup, latar belakang kebutuhan, dan hasil akhir yang diinginkan..."
                    class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-900 leading-relaxed"
                >{{ old('description') }}</textarea>
            </div>

            <!-- Status & Priority -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="status" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                        Status Awal <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        required 
                        class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white"
                    >
                        @foreach($statuses as $st)
                            <option value="{{ $st->value }}" {{ old('status', 'planning') === $st->value ? 'selected' : '' }}>
                                {{ $st->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="priority" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                        Tingkat Prioritas <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="priority" 
                        name="priority" 
                        required 
                        class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white"
                    >
                        <option value="low">Rendah</option>
                        <option value="medium" selected>Sedang</option>
                        <option value="high">Tinggi</option>
                        <option value="urgent">Mendesak (Urgent)</option>
                    </select>
                </div>
            </div>

            <!-- Deadline & Budget -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="deadline" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                        Target Selesai (Deadline)
                    </label>
                    <input 
                        type="date" 
                        id="deadline" 
                        name="deadline" 
                        value="{{ old('deadline', now()->addDays(30)->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white"
                    >
                </div>

                <div>
                    <label for="budget" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                        Estimasi Anggaran (Opsional)
                    </label>
                    <input 
                        type="text" 
                        id="budget" 
                        name="budget" 
                        value="{{ old('budget') }}"
                        placeholder="Contoh: Rp 150.000.000" 
                        class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white"
                    >
                </div>
            </div>

            <!-- Tech Stack -->
            <div>
                <label for="tech_stack" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                    Teknologi / Tools Terkait (Pisahkan dengan koma)
                </label>
                <input 
                    type="text" 
                    id="tech_stack" 
                    name="tech_stack" 
                    value="{{ old('tech_stack') }}"
                    placeholder="Contoh: Laravel, Postgres, Docker, Tailwind" 
                    class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white"
                >
            </div>

            <!-- Assign Team Lead -->
            <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <h4 class="font-bold text-zinc-900 dark:text-white text-xs mb-1">Penugasan Penanggung Jawab (Team Lead)</h4>
                <p class="text-[11px] text-zinc-400 mb-3">Team Lead yang dipilih akan dapat melihat project ini dan menyusun milestone serta menugaskan task ke Staff.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="team_lead_id" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                            Pilih Team Lead
                        </label>
                        <select 
                            id="team_lead_id" 
                            name="team_lead_id" 
                            class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white"
                        >
                            <option value="">-- Pilih Nanti --</option>
                            @foreach($teamLeads as $lead)
                                <option value="{{ $lead->id }}">
                                    {{ $lead->name }} ({{ $lead->roleDisplayName() }} - {{ $lead->division?->code ?? 'HQ' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="team_lead_role" class="block font-semibold mb-1.5 text-zinc-800 dark:text-zinc-200">
                            Peran di Project
                        </label>
                        <input 
                            type="text" 
                            id="team_lead_role" 
                            name="team_lead_role" 
                            value="Technical Lead / Project Lead"
                            class="w-full px-4 py-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white"
                        >
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('projects.index') }}" 
                    class="px-5 py-2.5 rounded-full text-xs font-semibold text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-full text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 shadow-sm transition-all hover:scale-105 active:scale-95 cursor-pointer"
                >
                    Buat Project Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
