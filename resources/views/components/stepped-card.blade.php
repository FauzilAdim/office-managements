@props([
    'isMaroon' => false,
    'avatar' => null,
    'title' => null,
    'subtitle' => null,
    'actions' => null,
])

<div {{ $attributes->merge(['class' => 'relative flex flex-col group transition-all duration-200 hover:-translate-y-1']) }}>
    <!-- Top Step Row: Left Avatar Tab + Fillet + Recessed Action Notch -->
    <div class="flex items-end relative z-10">
        <!-- Elevated Avatar Tab -->
        <div class="{{ $isMaroon ? 'bg-gradient-to-br from-[#DC2626] via-[#B91C1C] to-[#881337]' : 'bg-white' }} pt-3 px-3.5 pb-2.5 rounded-tl-[24px] rounded-tr-[20px] flex items-center gap-3 flex-1 min-w-0 shadow-xs border-t border-l border-r border-slate-200/90">
            @if($avatar)
                <img src="{{ $avatar }}" alt="Avatar" class="w-11 h-11 rounded-full object-cover ring-2 {{ $isMaroon ? 'ring-white/20' : 'ring-slate-100' }}" loading="lazy">
            @else
                <div class="w-11 h-11 rounded-full {{ $isMaroon ? 'bg-black/20 text-white' : 'bg-slate-100 text-slate-700' }} flex items-center justify-center font-bold text-xs ring-1 ring-slate-200">
                    OM
                </div>
            @endif

            @if($title || $subtitle)
                <div class="min-w-0 flex-1 pr-1">
                    @if($title)
                        <h3 class="text-sm sm:text-base font-bold {{ $isMaroon ? 'text-white' : 'text-slate-900' }} tracking-tight truncate leading-snug">{{ $title }}</h3>
                    @endif
                    @if($subtitle)
                        <p class="text-xs {{ $isMaroon ? 'text-rose-100' : 'text-slate-500' }} mt-0.5 leading-snug truncate">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Inverted Concave Fillet (Smooth Transition Curve) -->
        <div class="w-[20px] h-[20px] {{ $isMaroon ? 'text-[#DC2626]' : 'text-white' }} shrink-0 self-end -ml-px">
            <svg viewBox="0 0 20 20" class="w-full h-full fill-current">
                <path d="M 0 0 C 0 11.046 8.954 20 20 20 L 0 20 Z" />
            </svg>
        </div>

        <!-- Recessed Action Notch: Houses circular Bell and Arrow buttons -->
        <div class="shrink-0 flex items-center justify-end pb-2 pr-2.5 pl-1.5">
            {{ $actions ?? '' }}
        </div>
    </div>

    <!-- Main Card Body seamlessly fused to the tab and shelf -->
    <div class="{{ $isMaroon ? 'bg-gradient-to-br from-[#DC2626] via-[#B91C1C] to-[#881337] text-white shadow-lg shadow-rose-950/20 border-rose-700/40' : 'bg-white text-slate-800 shadow-sm hover:shadow-md border border-slate-200/90 hover:border-slate-300' }} -mt-px rounded-b-[24px] rounded-tr-[24px] p-5 pt-3.5 relative transition-all flex-1 flex flex-col justify-between">
        {{ $slot }}
    </div>
</div>
