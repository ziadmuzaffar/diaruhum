<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::with(['parent', 'children']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by parent
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
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

        // Include counts
        if ($request->boolean('include_counts')) {
            $query->withCount(['children', 'services', 'products']);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortDirection = $request->get('sort_direction', 'asc');

        if (in_array($sortBy, ['sort_order', 'name_ar', 'name_en', 'created_at', 'updated_at'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Add secondary sort
        if ($sortBy !== 'sort_order') {
            $query->orderBy('sort_order');
        }
        if ($sortBy !== 'name_ar') {
            $query->orderBy('name_ar');
        }

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $categories = $query->paginate($perPage);

        return response()->json([
            'data' => CategoryResource::collection($categories->items()),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ],
            'links' => [
                'first' => $categories->url(1),
                'last' => $categories->url($categories->lastPage()),
                'prev' => $categories->previousPageUrl(),
                'next' => $categories->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): JsonResponse
    {
        $category->load(['parent', 'children', 'services', 'products']);
        $category->loadCount(['children', 'services', 'products']);

        return response()->json([
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Get categories tree structure.
     */
    public function tree(Request $request): JsonResponse
    {
        $query = Category::with(['children' => function ($query) {
            $query->where('is_active', true)->orderBy('sort_order')->orderBy('name_ar');
        }])->whereNull('parent_id');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $categories = $query->where('is_active', true)
                           ->orderBy('sort_order')
                           ->orderBy('name_ar')
                           ->get();

        return response()->json([
            'data' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Get categories by type.
     */
    public function byType(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:design,labor,materials,logistics,realestate',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
        ]);

        $query = Category::where('type', $request->type);

        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        } else {
            $query->where('is_active', true);
        }

        $categories = $query->orderBy('sort_order')
                           ->orderBy('name_ar')
                           ->get();

        return response()->json([
            'data' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Get category statistics.
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'inactive_categories' => Category::where('is_active', false)->count(),
            'parent_categories' => Category::whereNull('parent_id')->count(),
            'child_categories' => Category::whereNotNull('parent_id')->count(),
            'by_type' => [
                'design' => Category::where('type', 'design')->count(),
                'labor' => Category::where('type', 'labor')->count(),
                'materials' => Category::where('type', 'materials')->count(),
                'logistics' => Category::where('type', 'logistics')->count(),
                'realestate' => Category::where('type', 'realestate')->count(),
            ],
            'with_images' => Category::whereNotNull('image')->count(),
            'with_icons' => Category::whereNotNull('icon')->count(),
        ];

        return response()->json([
            'data' => $stats,
        ]);
    }

    /**
     * Search categories.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255',
            'type' => 'nullable|in:design,labor,materials,logistics,realestate',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $query = Category::where('is_active', true);

        // Search in names and descriptions
        $searchTerm = $request->q;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name_ar', 'like', "%{$searchTerm}%")
              ->orWhere('name_en', 'like', "%{$searchTerm}%")
              ->orWhere('description_ar', 'like', "%{$searchTerm}%")
              ->orWhere('description_en', 'like', "%{$searchTerm}%")
              ->orWhere('slug', 'like', "%{$searchTerm}%");
        });

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $limit = $request->get('limit', 10);
        $categories = $query->orderBy('sort_order')
                           ->orderBy('name_ar')
                           ->limit($limit)
                           ->get();

        return response()->json([
            'data' => CategoryResource::collection($categories),
            'meta' => [
                'query' => $searchTerm,
                'total_results' => $categories->count(),
                'limit' => $limit,
            ],
        ]);
    }
}
