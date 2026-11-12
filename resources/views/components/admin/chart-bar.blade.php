@props([
    'label',
    'value',
    'percentage',
    'color' => 'primary'
])

<div class="flex items-center justify-between group">
    <span class="text-sm text-gray-600 min-w-0 flex-shrink-0">{{ $label }}</span>
    <div class="flex-1 mx-4">
        <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
            <div class="bg-{{ $color }}-600 h-2 rounded-full chart-bar transition-all duration-500 ease-out"
                 style="width: {{ $percentage }}%"></div>
        </div>
    </div>
    <span class="text-sm font-semibold text-gray-900 min-w-0 flex-shrink-0">{{ $value }}</span>
</div>
