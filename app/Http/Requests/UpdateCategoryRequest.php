<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add proper authorization logic here
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route('category')->id;

        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($categoryId)
            ],
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:design,labor,materials,logistics,realestate',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($categoryId) {
                    if ($value == $categoryId) {
                        $fail('لا يمكن أن تكون الفئة والدة لنفسها');
                    }
                    // Check for circular reference
                    if ($value && $this->wouldCreateCircularReference($categoryId, $value)) {
                        $fail('هذا الاختيار سيؤدي إلى مرجع دائري');
                    }
                }
            ],
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'metadata' => 'nullable|array',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name_ar.required' => 'الاسم باللغة العربية مطلوب',
            'name_ar.max' => 'الاسم باللغة العربية يجب ألا يتجاوز 255 حرف',
            'name_en.max' => 'الاسم باللغة الإنجليزية يجب ألا يتجاوز 255 حرف',
            'slug.unique' => 'هذا الرابط مستخدم بالفعل',
            'slug.max' => 'الرابط يجب ألا يتجاوز 255 حرف',
            'icon.max' => 'رمز الأيقونة يجب ألا يتجاوز 255 حرف',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
            'type.required' => 'نوع الفئة مطلوب',
            'type.in' => 'نوع الفئة غير صحيح',
            'parent_id.exists' => 'الفئة الأب المحددة غير موجودة',
            'sort_order.integer' => 'ترتيب الفئة يجب أن يكون رقم صحيح',
            'sort_order.min' => 'ترتيب الفئة يجب أن يكون أكبر من أو يساوي 0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name_ar' => 'الاسم باللغة العربية',
            'name_en' => 'الاسم باللغة الإنجليزية',
            'slug' => 'الرابط',
            'description_ar' => 'الوصف باللغة العربية',
            'description_en' => 'الوصف باللغة الإنجليزية',
            'icon' => 'الأيقونة',
            'image' => 'الصورة',
            'type' => 'النوع',
            'parent_id' => 'الفئة الأب',
            'sort_order' => 'ترتيب الفئة',
            'is_active' => 'الحالة',
            'metadata' => 'البيانات الإضافية',
        ];
    }

    /**
     * Check if setting parent would create circular reference.
     */
    private function wouldCreateCircularReference($categoryId, $parentId)
    {
        $parent = \App\Models\Category::find($parentId);

        while ($parent) {
            if ($parent->id === $categoryId) {
                return true;
            }
            $parent = $parent->parent;
        }

        return false;
    }
}
