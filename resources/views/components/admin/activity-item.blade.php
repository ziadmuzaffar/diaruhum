@props([
    'icon',
    'iconColor' => 'blue',
    'title',
    'description',
    'time',
    'amount' => null
])

<div class="flex items-start space-x-3 space-x-reverse activity-item">
    <div class="bg-{{ $iconColor }}-100 p-2 rounded-full flex-shrink-0">
        <i class="fas fa-{{ $icon }} text-{{ $iconColor }}-600 text-sm"></i>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-gray-900">{{ $title }}</p>
        <p class="text-sm text-gray-500">{{ $description }}</p>
        <p class="text-xs text-gray-400">{{ $time }}</p>
    </div>
    @if($amount)
        <span class="text-sm font-semibold text-green-600 flex-shrink-0">{{ $amount }}</span>
    @endif
</div>
