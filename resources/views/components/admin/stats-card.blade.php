@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'growth' => null,
    'subtitle' => null
])

<div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 stats-card">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 mb-1">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900 mb-2">{{ $value }}</p>

            @if($growth !== null)
                <p class="text-sm {{ $growth >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
                    <i class="fas fa-arrow-{{ $growth >= 0 ? 'up' : 'down' }} ml-1"></i>
                    {{ $growth >= 0 ? '+' : '' }}{{ $growth }}% من الشهر الماضي
                </p>
            @elseif($subtitle)
                <p class="text-sm text-{{ $color }}-600 flex items-center">
                    <i class="fas fa-info-circle ml-1"></i>
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <div class="bg-{{ $color }}-100 p-3 rounded-full stats-icon">
            <i class="fas fa-{{ $icon }} text-2xl text-{{ $color }}-600"></i>
        </div>
    </div>
</div>
