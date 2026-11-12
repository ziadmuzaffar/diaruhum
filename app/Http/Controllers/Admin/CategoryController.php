<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        $query = Category::with(['parent', 'children']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Sort by sort_order and name
        $categories = $query->orderBy('sort_order')
                           ->orderBy('name_ar')
                           ->paginate(15);

        $types = ['design', 'labor', 'materials', 'logistics', 'realestate'];
        $parentCategories = Category::whereNull('parent_id')
                                  ->where('is_active', true)
                                  ->orderBy('sort_order')
                                  ->get();

        return view('pages.admin.categories.index', compact('categories', 'types', 'parentCategories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $types = ['design', 'labor', 'materials', 'logistics', 'realestate'];
        $parentCategories = Category::whereNull('parent_id')
                                  ->where('is_active', true)
                                  ->orderBy('sort_order')
                                  ->get();

        return view('pages.admin.categories.create', compact('types', 'parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name_ar']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        // Set default sort order
        if (!isset($validated['sort_order'])) {
            $maxOrder = Category::where('parent_id', $validated['parent_id'] ?? null)->max('sort_order');
            $validated['sort_order'] = ($maxOrder ?? 0) + 1;
        }

        $category = Category::create($validated);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'تم إنشاء الفئة بنجاح');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'services', 'products']);

        return view('pages.admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $types = ['design', 'labor', 'materials', 'logistics', 'realestate'];
        $parentCategories = Category::whereNull('parent_id')
                                  ->where('is_active', true)
                                  ->where('id', '!=', $category->id)
                                  ->orderBy('sort_order')
                                  ->get();

        return view('pages.admin.categories.edit', compact('category', 'types', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name_ar']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && \Storage::disk('public')->exists($category->image)) {
                \Storage::disk('public')->delete($category->image);
            }

            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'تم تحديث الفئة بنجاح');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has children
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                           ->with('error', 'لا يمكن حذف فئة تحتوي على فئات فرعية');
        }

        // Check if category has associated services or products
        if ($category->services()->count() > 0 || $category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                           ->with('error', 'لا يمكن حذف فئة مرتبطة بخدمات أو منتجات');
        }

        // Delete image if exists
        if ($category->image && \Storage::disk('public')->exists($category->image)) {
            \Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                        ->with('success', 'تم حذف الفئة بنجاح');
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        return redirect()->back()
                        ->with('success', "{$status} الفئة بنجاح");
    }

    /**
     * Update categories sort order.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->categories as $categoryData) {
            Category::where('id', $categoryData['id'])
                   ->update(['sort_order' => $categoryData['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'تم تحديث ترتيب الفئات بنجاح']);
    }

    /**
     * Get categories by type (AJAX).
     */
    public function getByType(Request $request)
    {
        $type = $request->get('type');
        $parentId = $request->get('parent_id');

        $query = Category::where('type', $type)->where('is_active', true);

        if ($parentId) {
            $query->where('parent_id', $parentId);
        }

        $categories = $query->orderBy('sort_order')->orderBy('name_ar')->get();

        return response()->json($categories);
    }
}
