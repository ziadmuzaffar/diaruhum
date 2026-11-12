@extends('layouts.admin')

@section('title', 'عرض الفئة: ' . $category->name_ar)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $category->name_ar }}</h1>
            <p class="text-gray-600 mt-1">تفاصيل الفئة ومعلوماتها</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                العودة للقائمة
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-start justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">المعلومات الأساسية</h2>
                    <div class="flex items-center gap-2">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}"
                                 alt="{{ $category->name_ar }}"
                                 class="w-16 h-16 rounded-lg object-cover border">
                        @elseif($category->icon)
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="{{ $category->icon }} text-2xl text-gray-600"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">الاسم باللغة العربية</label>
                        <p class="text-gray-900 font-medium">{{ $category->name_ar }}</p>
                    </div>

                    @if($category->name_en)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">الاسم باللغة الإنجليزية</label>
                            <p class="text-gray-900">{{ $category->name_en }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">الرابط (Slug)</label>
                        <p class="text-gray-900 font-mono text-sm">{{ $category->slug }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">نوع الفئة</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            @switch($category->type)
                                @case('design') bg-purple-100 text-purple-800 @break
                                @case('labor') bg-blue-100 text-blue-800 @break
                                @case('materials') bg-green-100 text-green-800 @break
                                @case('logistics') bg-yellow-100 text-yellow-800 @break
                                @case('realestate') bg-red-100 text-red-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            @switch($category->type)
                                @case('design') تصميم @break
                                @case('labor') عمالة @break
                                @case('materials') مواد @break
                                @case('logistics') لوجستيات @break
                                @case('realestate') عقارات @break
                                @default {{ $category->type }}
                            @endswitch
                        </span>
                    </div>

                    @if($category->parent)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">الفئة الأب</label>
                            <a href="{{ route('admin.categories.show', $category->parent) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $category->parent->name_ar }}
                            </a>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">ترتيب الفئة</label>
                        <p class="text-gray-900">{{ $category->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">الحالة</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>

                    @if($category->icon)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">رمز الأيقونة</label>
                            <div class="flex items-center gap-2">
                                <i class="{{ $category->icon }} text-gray-600"></i>
                                <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $category->icon }}</code>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Descriptions -->
            @if($category->description_ar || $category->description_en)
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">الوصف</h2>

                    @if($category->description_ar)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-2">الوصف باللغة العربية</label>
                            <div class="text-gray-900 leading-relaxed">
                                {!! nl2br(e($category->description_ar)) !!}
                            </div>
                        </div>
                    @endif

                    @if($category->description_en)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">الوصف باللغة الإنجليزية</label>
                            <div class="text-gray-900 leading-relaxed">
                                {!! nl2br(e($category->description_en)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Child Categories -->
            @if($category->children->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">الفئات الفرعية ({{ $category->children->count() }})</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($category->children as $child)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @if($child->image)
                                            <img src="{{ Storage::url($child->image) }}"
                                                 alt="{{ $child->name_ar }}"
                                                 class="w-10 h-10 rounded-lg object-cover">
                                        @elseif($child->icon)
                                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="{{ $child->icon }} text-gray-600"></i>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $child->name_ar }}</h3>
                                            <p class="text-sm text-gray-500">{{ $child->slug }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $child->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $child->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                        <a href="{{ route('admin.categories.show', $child) }}"
                                           class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.categories.edit', $category) }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        تعديل الفئة
                    </a>

                    <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full {{ $category->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg flex items-center gap-2 justify-center">
                            @if($category->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                                إلغاء التفعيل
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                تفعيل الفئة
                            @endif
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟ سيتم حذف جميع الفئات الفرعية أيضاً.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            حذف الفئة
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">الإحصائيات</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">الفئات الفرعية</span>
                        <span class="font-semibold text-gray-900">{{ $category->children->count() }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">الخدمات</span>
                        <span class="font-semibold text-gray-900">{{ $category->services->count() }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">المنتجات</span>
                        <span class="font-semibold text-gray-900">{{ $category->products->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات النظام</h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500">تاريخ الإنشاء:</span>
                        <p class="text-gray-900">{{ $category->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div>
                        <span class="text-gray-500">آخر تحديث:</span>
                        <p class="text-gray-900">{{ $category->updated_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div>
                        <span class="text-gray-500">معرف الفئة:</span>
                        <p class="text-gray-900 font-mono">#{{ $category->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
