<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CategoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:manage
                            {action : The action to perform (list, create, update, delete, reorder)}
                            {--id= : Category ID for update/delete operations}
                            {--name_ar= : Arabic name for the category}
                            {--name_en= : English name for the category}
                            {--type= : Category type (design, labor, materials, logistics, realestate)}
                            {--parent_id= : Parent category ID}
                            {--active : Set category as active}
                            {--inactive : Set category as inactive}
                            {--force : Force delete without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage categories from command line';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        return match ($action) {
            'list' => $this->listCategories(),
            'create' => $this->createCategory(),
            'update' => $this->updateCategory(),
            'delete' => $this->deleteCategory(),
            'reorder' => $this->reorderCategories(),
            default => $this->error("Unknown action: {$action}")
        };
    }

    /**
     * List all categories.
     */
    private function listCategories()
    {
        $categories = Category::with('parent')
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get();

        if ($categories->isEmpty()) {
            $this->info('No categories found.');
            return 0;
        }

        $headers = ['ID', 'Name (AR)', 'Name (EN)', 'Type', 'Parent', 'Status', 'Order'];
        $rows = [];

        foreach ($categories as $category) {
            $rows[] = [
                $category->id,
                $category->name_ar,
                $category->name_en ?: '-',
                $category->type,
                $category->parent ? $category->parent->name_ar : '-',
                $category->is_active ? 'Active' : 'Inactive',
                $category->sort_order,
            ];
        }

        $this->table($headers, $rows);
        $this->info("Total categories: " . $categories->count());

        return 0;
    }

    /**
     * Create a new category.
     */
    private function createCategory()
    {
        $nameAr = $this->option('name_ar') ?: $this->ask('Arabic name');
        $nameEn = $this->option('name_en') ?: $this->ask('English name (optional)');
        $type = $this->option('type') ?: $this->choice('Category type', [
            'design', 'labor', 'materials', 'logistics', 'realestate'
        ]);

        $parentId = $this->option('parent_id');
        if (!$parentId && $this->confirm('Does this category have a parent?')) {
            $parents = Category::whereNull('parent_id')->where('type', $type)->get();
            if ($parents->isNotEmpty()) {
                $parentChoices = $parents->pluck('name_ar', 'id')->toArray();
                $parentId = $this->choice('Select parent category', $parentChoices);
            }
        }

        $isActive = $this->option('active') ?:
                   (!$this->option('inactive') && $this->confirm('Is this category active?', true));

        try {
            $category = Category::create([
                'name_ar' => $nameAr,
                'name_en' => $nameEn,
                'slug' => Str::slug($nameAr),
                'type' => $type,
                'parent_id' => $parentId,
                'is_active' => $isActive,
                'sort_order' => Category::where('parent_id', $parentId)->max('sort_order') + 1,
            ]);

            $this->info("Category created successfully with ID: {$category->id}");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to create category: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Update an existing category.
     */
    private function updateCategory()
    {
        $id = $this->option('id') ?: $this->ask('Category ID');

        $category = Category::find($id);
        if (!$category) {
            $this->error("Category with ID {$id} not found.");
            return 1;
        }

        $this->info("Current category: {$category->name_ar}");

        $updates = [];

        if ($nameAr = $this->option('name_ar')) {
            $updates['name_ar'] = $nameAr;
            $updates['slug'] = Str::slug($nameAr);
        }

        if ($nameEn = $this->option('name_en')) {
            $updates['name_en'] = $nameEn;
        }

        if ($type = $this->option('type')) {
            $updates['type'] = $type;
        }

        if ($this->option('active')) {
            $updates['is_active'] = true;
        } elseif ($this->option('inactive')) {
            $updates['is_active'] = false;
        }

        if (empty($updates)) {
            $this->warn('No updates specified.');
            return 0;
        }

        try {
            $category->update($updates);
            $this->info('Category updated successfully.');
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to update category: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Delete a category.
     */
    private function deleteCategory()
    {
        $id = $this->option('id') ?: $this->ask('Category ID');

        $category = Category::with(['children', 'services', 'products'])->find($id);
        if (!$category) {
            $this->error("Category with ID {$id} not found.");
            return 1;
        }

        $this->info("Category: {$category->name_ar}");

        // Check for dependencies
        $hasChildren = $category->children->count() > 0;
        $hasServices = $category->services->count() > 0;
        $hasProducts = $category->products->count() > 0;

        if ($hasChildren) {
            $this->error("Cannot delete category with {$category->children->count()} child categories.");
            return 1;
        }

        if ($hasServices || $hasProducts) {
            $this->error("Cannot delete category with associated services ({$category->services->count()}) or products ({$category->products->count()}).");
            return 1;
        }

        if (!$this->option('force') && !$this->confirm('Are you sure you want to delete this category?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        try {
            $category->delete();
            $this->info('Category deleted successfully.');
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to delete category: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Reorder categories by sort_order.
     */
    private function reorderCategories()
    {
        $this->info('Reordering categories by type and name...');

        $types = ['design', 'labor', 'materials', 'logistics', 'realestate'];
        $totalUpdated = 0;

        foreach ($types as $type) {
            // Reorder parent categories
            $parents = Category::where('type', $type)
                ->whereNull('parent_id')
                ->orderBy('name_ar')
                ->get();

            foreach ($parents as $index => $parent) {
                $parent->update(['sort_order' => $index + 1]);
                $totalUpdated++;

                // Reorder child categories
                $children = Category::where('parent_id', $parent->id)
                    ->orderBy('name_ar')
                    ->get();

                foreach ($children as $childIndex => $child) {
                    $child->update(['sort_order' => $childIndex + 1]);
                    $totalUpdated++;
                }
            }
        }

        $this->info("Reordered {$totalUpdated} categories successfully.");
        return 0;
    }
}
