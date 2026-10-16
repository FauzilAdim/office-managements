@extends('layouts.app')

@section('title', 'Edit Profil - ' . $employee->name)
@section('page_title', 'Perbarui Profil Karyawan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center space-x-2 text-xs text-slate-500">
        <a href="{{ route('employees.show', $employee) }}" class="hover:text-blue-600">&larr; Kembali ke Profil</a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700/80 shadow-sm">
        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-4 mb-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Edit Informasi Profil</h2>
            <p class="text-xs text-slate-500 mt-1">Perbarui data diri kontak, nomor telepon, alamat, dan foto profil.</p>
        </div>

        <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Avatar Preview & Upload -->
            <div class="flex items-center space-x-5">
                <img src="{{ $employee->avatarUrl() }}" alt="{{ $employee->name }}" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-blue-500/30">
                <div>
                    <label for="avatar" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                        Ubah Foto Profil (Opsional)
                    </label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-950 dark:file:text-blue-300">
                    <p class="text-[11px] text-slate-400 mt-1">Maksimum ukuran gambar 2MB (JPG, PNG, WebP)</p>
                </div>
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" required value="{{ old('name', $employee->name) }}" class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <input type="email" id="email" name="email" required value="{{ old('email', $employee->email) }}" class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    Nomor Handphone / WhatsApp
                </label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" placeholder="contoh: 0812-3456-7890" class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    Alamat Domisili
                </label>
                <textarea id="address" name="address" rows="3" placeholder="Alamat tempat tinggal..." class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $employee->address) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end space-x-3">
                <a href="{{ route('employees.show', $employee) }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
