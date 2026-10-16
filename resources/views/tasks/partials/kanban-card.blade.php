@php
    $isCompletedThisMonth = $task->status === \App\Enums\TaskStatus::Completed && (($task->completed_at && $task->completed_at->isCurrentMonth()) || ($task->updated_at && $task->updated_at->isCurrentMonth()));
@endphp

<div 
    x-show="taskMatchesTab('{{ $task->status->value }}', '{{ $task->priority->value }}', {{ $isCompletedThisMonth ? 'true' : 'false' }})"
    draggable="true"
    @dragstart="draggedTaskId = {{ $task->id }}; $el.classList.add('opacity-50')"
    @dragend="$el.classList.remove('opacity-50')"
    class="rounded-xl bg-white dark:bg-[#18181D] border border-zinc-200/90 dark:border-zinc-800 p-3.5 shadow-2xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-150 cursor-grab active:cursor-grabbing group relative select-none"
>
    <!-- Label Kategori Kecil di Atas (Nama/Kode Divisi Tugas) -->
    <div class="flex items-center justify-between gap-1 mb-1.5">
        <span class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
            {{ $task->assignee?->division?->name ?? ($task->assignee?->division?->code ?? 'TECH') }}
        </span>
        @if($task->isOverdue())
            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200/60">
                Lewat Deadline
            </span>
        @endif
    </div>

    <!-- Judul Tugas Bold (1-2 baris) -->
    <a href="{{ route('tasks.show', $task) }}" class="block font-bold text-xs text-zinc-900 dark:text-zinc-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2 leading-snug mb-3">
        {{ $task->title }}
    </a>

    <!-- Baris Avatar: Avatar staff yang ditugaskan + Badge Prioritas di Kanan -->
    <div class="flex items-center justify-between mb-3">
        <!-- Avatar Staff yang Ditugaskan -->
        <div class="flex items-center space-x-1.5 min-w-0">
            @if($task->assignee)
                <img 
                    src="{{ $task->assignee->avatarUrl() }}" 
                    alt="{{ $task->assignee->name }}" 
                    title="Ditugaskan kepada: {{ $task->assignee->name }}"
                    class="h-6 w-6 rounded-full ring-2 ring-white dark:ring-[#18181D] object-cover shrink-0"
                >
                <span class="text-[11px] text-zinc-600 dark:text-zinc-300 font-medium truncate max-w-[110px]">
                    {{ $task->assignee->name }}
                </span>
            @else
                <span class="text-[11px] text-zinc-400 italic">Belum ditugaskan</span>
            @endif
        </div>

        <!-- Badge Prioritas Kecil Rounded-Full di Kanan (Warna Soft: merah muda=Tinggi, kuning muda=Sedang, abu=Rendah) -->
        @if(in_array($task->priority->value, ['high', 'urgent'], true))
            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200/80 dark:border-rose-900/40">
                Tinggi
            </span>
        @elseif($task->priority->value === 'medium')
            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200/80 dark:border-amber-900/40">
                Sedang
            </span>
        @else
            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                Rendah
            </span>
        @endif
    </div>

    <!-- Footer Card: 2-3 Icon Kecil dengan Angka di Kiri + Tag Deadline di Kanan -->
    <div class="pt-2.5 border-t border-zinc-100 dark:border-zinc-800/80 flex items-center justify-between text-xs text-zinc-400 dark:text-zinc-500">
        <!-- Icon Komentar + Icon Lampiran/Progress -->
        <div class="flex items-center space-x-2.5 text-[11px]">
            <!-- Komentar / Logs -->
            <span class="flex items-center space-x-1 hover:text-zinc-600 dark:hover:text-zinc-300" title="{{ $task->logs->count() }} Komentar & Riwayat">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>{{ $task->logs->count() }}</span>
            </span>

            <!-- Lampiran / Sub-tugas -->
            <span class="flex items-center space-x-1 hover:text-zinc-600 dark:hover:text-zinc-300" title="Lampiran / Sub-tugas">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                <span>0</span>
            </span>
        </div>

        <!-- Tag Deadline Kecil di Kanan (misal "Besok" / "26 Sep") -->
        <div class="text-[10px] font-medium">
            @php
                $isToday = $task->deadline->isToday();
                $isTomorrow = $task->deadline->isTomorrow();
            @endphp
            @if($task->isOverdue())
                <span class="text-rose-600 dark:text-rose-400 font-semibold flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $task->deadline->format('d M') }}</span>
                </span>
            @elseif($isToday)
                <span class="text-amber-600 dark:text-amber-400 font-semibold">Hari ini</span>
            @elseif($isTomorrow)
                <span class="text-indigo-600 dark:text-indigo-400 font-medium">Besok</span>
            @else
                <span class="text-zinc-500 dark:text-zinc-400">{{ $task->deadline->translatedFormat('d M') }}</span>
            @endif
        </div>
    </div>
</div>

