@extends('layouts.admin')

@section('title', 'تعديل الفئة: ' . $category->name_ar)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تعديل الفئة: {{ $category->name_ar }}</h1>
            <p class="text-gray-600 mt-1">تحديث معلومات الفئة</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.categories.show', $category) }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                عرض
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

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">المعلومات الأساسية</h3>

                    <!-- Arabic Name -->
                    <div>
                        <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            الاسم باللغة العربية <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar', $category->name_ar) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name_ar') border-red-500 @enderror">
                        @error('name_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- English Name -->
                    <div>
                        <label for="name_en" class="block text-sm font-medium text-gray-700 mb-2">
                            الاسم باللغة الإنجليزية
                        </label>
                        <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $category->name_en) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name_en') border-red-500 @enderror">
                        @error('name_en')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            الرابط (Slug)
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            نوع الفئة <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                            <option value="">اختر نوع الفئة</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ old('type', $category->type) === $type ? 'selected' : '' }}>
                                    @switch($type)
                                        @case('design') تصميم @break
                                        @case('labor') عمالة @break
                                        @case('materials') مواد @break
                                        @case('logistics') لوجستيات @break
                                        @case('realestate') عقارات @break
                                        @default {{ $type }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Category -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                            الفئة الأب
                        </label>
                        <select name="parent_id" id="parent_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('parent_id') border-red-500 @enderror">
                            <option value="">لا يوجد (فئة رئيسية)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">معلومات إضافية</h3>

                    <!-- Current Image -->
                    @if($category->image)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الصورة الحالية</label>
                            <div class="flex items-center gap-4">
                                <img src="{{ Storage::url($category->image) }}"
                                     alt="{{ $category->name_ar }}"
                                     class="w-20 h-20 rounded-lg object-cover border">
                                <div class="text-sm text-gray-600">
                                    <p>{{ basename($category->image) }}</p>
                                    <p class="text-xs">اختر صورة جديدة لاستبدال الحالية</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Icon -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            رمز الأيقونة (CSS Class)
                        </label>
                        <div class="flex items-center gap-3">
                            @if($category->icon)
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="{{ $category->icon }} text-gray-600"></i>
                                </div>
                            @endif
                            <input type="text" name="icon" id="icon" value="{{ old('icon', $category->icon) }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('icon') border-red-500 @enderror"
                                   placeholder="مثال: fas fa-home">
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $category->image ? 'تغيير صورة الفئة' : 'صورة الفئة' }}
                        </label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('image') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">الحد الأقصى: 2 ميجابايت. الأنواع المدعومة: JPEG, PNG, JPG, GIF</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            ترتيب الفئة
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-500 @enderror">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-gray-700">فئة نشطة</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Descriptions -->
            <div class="mt-6 space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">الوصف</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Arabic Description -->
                    <div>
                        <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            الوصف باللغة العربية
                        </label>
                        <textarea name="description_ar" id="description_ar" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description_ar') border-red-500 @enderror">{{ old('description_ar', $category->description_ar) }}</textarea>
                        @error('description_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- English Description -->
                    <div>
                        <label for="description_en" class="block text-sm font-medium text-gray-700 mb-2">
                            الوصف باللغة الإنجليزية
                        </label>
                        <textarea name="description_en" id="description_en" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description_en') border-red-500 @enderror">{{ old('description_en', $category->description_en) }}</textarea>
                        @error('description_en')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex items-center justify-end gap-4 pt-6 border-t">
                <a href="{{ route('admin.categories.show', $category) }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg">
                    إلغاء
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from Arabic name if slug is empty
    const nameArInput = document.getElementById('name_ar');
    const slugInput = document.getElementById('slug');
    const originalSlug = slugInput.value;

    nameArInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated) {
            const slug = this.value
                .toLowerCase()
                .replace(/[أإآ]/g, 'ا')
                .replace(/[ة]/g, 'ه')
                .replace(/[ى]/g, 'ي')
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]/g, '');

            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });

    slugInput.addEventListener('input', function() {
        if (this.value !== originalSlug) {
            delete this.dataset.autoGenerated;
        }
    });
});
</script>
@endpush
@endsection
