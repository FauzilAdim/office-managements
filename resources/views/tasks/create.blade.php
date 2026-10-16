@extends('layouts.app')

@section('title', 'Tugaskan Pekerjaan Baru')
@section('page_title', 'Buat & Delegasikan Tugas')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center space-x-2 text-xs text-slate-500">
        <a href="{{ route('tasks.index') }}" class="hover:text-blue-600">&larr; Kembali ke Daftar Tugas</a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700/80 shadow-sm">
        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-4 mb-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Form Penugasan Pekerjaan</h2>
            <p class="text-xs text-slate-500 mt-1">Delegasikan tugas kepada bawahan sesuai struktur hierarki wewenang Anda.</p>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    Judul Tugas / Pekerjaan <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="title" name="title" required value="{{ old('title') }}" placeholder="contoh: Integrasi Payment Gateway QRIS" class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    Deskripsi & Kebutuhan Tugas
                </label>
                <textarea id="description" name="description" rows="4" placeholder="Jelaskan instruksi pengerjaan, spesifikasi teknis, atau target output yang diharapkan..." class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Assignee (Penerima Tugas) -->
                <div>
                    <label for="assigned_to" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Penerima Tugas (Bawahan / Subordinat) <span class="text-rose-500">*</span>
                    </label>
                    <select id="assigned_to" name="assigned_to" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Karyawan Penerima --</option>
                        @foreach($assignableUsers as $u)
                            <option value="{{ $u->id }}" {{ old('assigned_to') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->roleDisplayName() }} - {{ $u->position ?? $u->division?->code }})
                            </option>
                        @endforeach
                    </select>
                    @if($assignableUsers->isEmpty())
                        <p class="text-xs text-rose-500 mt-1">Anda belum memiliki bawahan langsung yang dapat ditugaskan.</p>
                    @endif
                </div>

                <!-- Deadline -->
                <div>
                    <label for="deadline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Batas Waktu (Deadline) <span class="text-rose-500">*</span>
                    </label>
                    <input type="datetime-local" id="deadline" name="deadline" required value="{{ old('deadline', now()->addDays(2)->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Tingkat Prioritas <span class="text-rose-500">*</span>
                    </label>
                    <select id="priority" name="priority" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($priorities as $pr)
                            <option value="{{ $pr->value }}" {{ old('priority', 'medium') === $pr->value ? 'selected' : '' }}>
                                {{ $pr->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Initial Status -->
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Status Awal
                    </label>
                    <select id="status" name="status" class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($statuses as $st)
                            <option value="{{ $st->value }}" {{ old('status', 'pending') === $st->value ? 'selected' : '' }}>
                                {{ $st->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end space-x-3">
                <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all hover:scale-[1.02]">
                    Simpan & Tugaskan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
