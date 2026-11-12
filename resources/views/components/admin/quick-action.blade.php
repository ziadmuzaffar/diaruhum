@props([
    'icon',
    'title',
    'color' => 'blue',
    'href' => '#'
])

<a href="{{ $href }}" class="flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-{{ $color }}-300 hover:bg-{{ $color }}-50 transition-all duration-300 group quick-action">
    <div class="bg-{{ $color }}-100 p-3 rounded-full mb-3 group-hover:bg-{{ $color }}-200 transition-colors duration-300">
        <i class="fas fa-{{ $icon }} text-{{ $color }}-600"></i>
    </div>
    <span class="text-sm font-medium text-gray-700 text-center group-hover:text-{{ $color }}-700 transition-colors duration-300">{{ $title }}</span>
</a>
