<div class="flex flex-col items-center">
    <!-- Node Card (Consistent Framer-Style Glass) -->
    <div class="relative bg-white/95 dark:bg-zinc-800/95 backdrop-blur-md rounded-2xl p-4 border border-slate-200/90 dark:border-zinc-700 shadow-sm hover:shadow-lg w-64 transition-all duration-200 hover:-translate-y-0.5 group">
        <!-- Role Pill -->
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ 
                $node['role'] === 'Direktur' ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border border-indigo-200/80 dark:border-indigo-900/40' : (
                $node['role'] === 'VP' ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border border-blue-200/80 dark:border-blue-900/40' : (
                $node['role'] === 'Manager' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-900/40' : (
                $node['role'] === 'Team Lead' ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200/80 dark:border-amber-900/40' : 
                'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-600'
            ))) }}">
                {{ $node['role'] }}
            </span>
            <span class="text-[10px] font-semibold text-slate-400 dark:text-zinc-500">
                {{ $node['division_code'] }}
            </span>
        </div>

        <div class="flex items-center space-x-3">
            <img src="{{ $node['avatar'] }}" alt="{{ $node['name'] }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-white/90 dark:ring-zinc-700 shadow-xs">
            <div class="min-w-0 flex-1">
                <a href="{{ route('employees.show', $node['id']) }}" class="text-xs font-bold text-slate-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400 truncate block">
                    {{ $node['name'] }}
                </a>
                <p class="text-[11px] text-slate-500 dark:text-zinc-400 truncate mt-0.5">{{ $node['position'] }}</p>
            </div>
        </div>

        @if(!empty($node['active_task']))
            <div class="mt-2.5 pt-2 border-t border-slate-100 dark:border-zinc-700/60 flex items-center space-x-1.5 text-[10px] text-emerald-600 dark:text-emerald-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="truncate font-medium">Aktif: {{ $node['active_task'] }}</span>
            </div>
        @endif
    </div>

    <!-- Children Nodes -->
    @if(!empty($node['children']))
        <!-- Downward connecting stem -->
        <div class="w-0.5 h-6 bg-slate-300 dark:bg-zinc-700"></div>

        <div class="relative flex justify-center pt-4">
            <!-- Horizontal branch bar -->
            @if(count($node['children']) > 1)
                <div class="absolute top-0 left-1/2 -translate-x-1/2 h-0.5 bg-slate-300 dark:bg-zinc-700" style="width: calc(100% - 16rem);"></div>
            @endif

            <div class="flex space-x-6 items-start">
                @foreach($node['children'] as $child)
                    <div class="flex flex-col items-center">
                        <div class="w-0.5 h-4 bg-slate-300 dark:bg-zinc-700 -mt-4"></div>
                        @include('organization.tree-node', ['node' => $child])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
