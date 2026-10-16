<div class="flex flex-col items-center">
    <!-- Node Card -->
    <div class="relative bg-white rounded-2xl p-4 border-2 {{ $node['role'] === 'Direktur' ? 'border-[#881337] shadow-rose-900/10' : ($node['role'] === 'VP' ? 'border-[#DC2626] shadow-red-500/10' : ($node['role'] === 'Manager' ? 'border-rose-400' : ($node['role'] === 'Team Lead' ? 'border-slate-400' : 'border-slate-200'))) }} shadow-lg w-64 hover:scale-[1.02] transition-transform">
        <!-- Role Pill -->
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $node['role'] === 'Direktur' ? 'bg-rose-100 text-[#881337]' : ($node['role'] === 'VP' ? 'bg-red-100 text-red-800' : ($node['role'] === 'Manager' ? 'bg-rose-50 text-rose-700' : ($node['role'] === 'Team Lead' ? 'bg-slate-100 text-slate-800' : 'bg-slate-100 text-slate-700'))) }}">
                {{ $node['role'] }}
            </span>
            <span class="text-[10px] font-semibold text-slate-400">
                {{ $node['division_code'] }}
            </span>
        </div>

        <div class="flex items-center space-x-3">
            <img src="{{ $node['avatar'] }}" alt="{{ $node['name'] }}" class="w-11 h-11 rounded-xl object-cover ring-2 ring-rose-500/20 shadow-sm">
            <div class="min-w-0 flex-1">
                <a href="{{ route('employees.show', $node['id']) }}" class="text-xs font-bold text-slate-900 hover:text-[#881337] truncate block">
                    {{ $node['name'] }}
                </a>
                <p class="text-[11px] text-slate-500 truncate">{{ $node['position'] }}</p>
            </div>
        </div>

        @if(!empty($node['active_task']))
            <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center space-x-1.5 text-[10px] text-[#881337]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#DC2626] animate-pulse"></span>
                <span class="truncate">Aktif: {{ $node['active_task'] }}</span>
            </div>
        @endif
    </div>

    <!-- Children Nodes -->
    @if(!empty($node['children']))
        <!-- Downward connecting stem -->
        <div class="w-0.5 h-6 bg-slate-300"></div>

        <div class="relative flex justify-center pt-4">
            <!-- Horizontal branch bar -->
            @if(count($node['children']) > 1)
                <div class="absolute top-0 left-1/2 -translate-x-1/2 h-0.5 bg-slate-300" style="width: calc(100% - 16rem);"></div>
            @endif

            <div class="flex space-x-6 items-start">
                @foreach($node['children'] as $child)
                    <div class="flex flex-col items-center">
                        <div class="w-0.5 h-4 bg-slate-300 -mt-4"></div>
                        @include('organization.tree-node', ['node' => $child])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
