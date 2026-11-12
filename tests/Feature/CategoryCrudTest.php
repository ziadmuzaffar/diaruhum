<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_can_display_categories_index()
    {
        Category::factory()->count(5)->create();

        $response = $this->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.categories.index');
        $response->assertViewHas('categories');
    }

    /** @test */
    public function it_can_filter_categories_by_type()
    {
        Category::factory()->create(['type' => 'design']);
        Category::factory()->create(['type' => 'labor']);

        $response = $this->get(route('admin.categories.index', ['type' => 'design']));

        $response->assertStatus(200);
        $response->assertSee('design');
    }

    /** @test */
    public function it_can_search_categories()
    {
        Category::factory()->create(['name_ar' => 'تصميم داخلي']);
        Category::factory()->create(['name_ar' => 'أعمال سباكة']);

        $response = $this->get(route('admin.categories.index', ['search' => 'تصميم']));

        $response->assertStatus(200);
        $response->assertSee('تصميم داخلي');
        $response->assertDontSee('أعمال سباكة');
    }

    /** @test */
    public function it_can_show_create_category_form()
    {
        $response = $this->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.categories.create');
        $response->assertViewHas(['types', 'parentCategories']);
    }

    /** @test */
    public function it_can_store_a_new_category()
    {
        $categoryData = [
            'name_ar' => 'فئة جديدة',
            'name_en' => 'New Category',
            'type' => 'design',
            'description_ar' => 'وصف الفئة',
            'is_active' => true,
        ];

        $response = $this->post(route('admin.categories.store'), $categoryData);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name_ar' => 'فئة جديدة',
            'type' => 'design',
        ]);
    }

    /** @test */
    public function it_can_store_category_with_image()
    {
        $image = UploadedFile::fake()->image('category.jpg');

        $categoryData = [
            'name_ar' => 'فئة مع صورة',
            'type' => 'design',
            'image' => $image,
            'is_active' => true,
        ];

        $response = $this->post(route('admin.categories.store'), $categoryData);

        $response->assertRedirect(route('admin.categories.index'));

        $category = Category::where('name_ar', 'فئة مع صورة')->first();
        $this->assertNotNull($category->image);
        Storage::disk('public')->assertExists($category->image);
    }

    /** @test */
    public function it_generates_slug_automatically()
    {
        $categoryData = [
            'name_ar' => 'فئة تجريبية',
            'type' => 'design',
            'is_active' => true,
        ];

        $this->post(route('admin.categories.store'), $categoryData);

        $this->assertDatabaseHas('categories', [
            'name_ar' => 'فئة تجريبية',
            'slug' => 'فئة-تجريبية',
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->post(route('admin.categories.store'), []);

        $response->assertSessionHasErrors(['name_ar', 'type']);
    }

    /** @test */
    public function it_validates_unique_slug()
    {
        Category::factory()->create(['slug' => 'existing-slug']);

        $categoryData = [
            'name_ar' => 'فئة جديدة',
            'type' => 'design',
            'slug' => 'existing-slug',
            'is_active' => true,
        ];

        $response = $this->post(route('admin.categories.store'), $categoryData);

        $response->assertSessionHasErrors(['slug']);
    }

    /** @test */
    public function it_can_show_category_details()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('admin.categories.show', $category));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.categories.show');
        $response->assertViewHas('category');
        $response->assertSee($category->name_ar);
    }

    /** @test */
    public function it_can_show_edit_category_form()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('admin.categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.categories.edit');
        $response->assertViewHas(['category', 'types', 'parentCategories']);
    }

    /** @test */
    public function it_can_update_category()
    {
        $category = Category::factory()->create(['name_ar' => 'اسم قديم']);

        $updateData = [
            'name_ar' => 'اسم جديد',
            'name_en' => 'New Name',
            'type' => $category->type,
            'is_active' => true,
        ];

        $response = $this->put(route('admin.categories.update', $category), $updateData);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name_ar' => 'اسم جديد',
        ]);
    }

    /** @test */
    public function it_can_update_category_image()
    {
        $category = Category::factory()->create();
        $newImage = UploadedFile::fake()->image('new-image.jpg');

        $updateData = [
            'name_ar' => $category->name_ar,
            'type' => $category->type,
            'image' => $newImage,
            'is_active' => true,
        ];

        $response = $this->put(route('admin.categories.update', $category), $updateData);

        $response->assertRedirect(route('admin.categories.index'));

        $category->refresh();
        $this->assertNotNull($category->image);
        Storage::disk('public')->assertExists($category->image);
    }

    /** @test */
    public function it_can_toggle_category_status()
    {
        $category = Category::factory()->create(['is_active' => true]);

        $response = $this->patch(route('admin.categories.toggle-status', $category));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $category->refresh();
        $this->assertFalse($category->is_active);
    }

    /** @test */
    public function it_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_cannot_delete_category_with_children()
    {
        $parent = Category::factory()->create();
        $child = Category::factory()->create(['parent_id' => $parent->id]);

        $response = $this->delete(route('admin.categories.destroy', $parent));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $parent->id]);
    }

    /** @test */
    public function it_can_update_categories_order()
    {
        $category1 = Category::factory()->create(['sort_order' => 1]);
        $category2 = Category::factory()->create(['sort_order' => 2]);

        $orderData = [
            'categories' => [
                ['id' => $category2->id, 'sort_order' => 1],
                ['id' => $category1->id, 'sort_order' => 2],
            ]
        ];

        $response = $this->post(route('admin.categories.update-order'), $orderData);

        $response->assertJson(['success' => true]);

        $category1->refresh();
        $category2->refresh();

        $this->assertEquals(2, $category1->sort_order);
        $this->assertEquals(1, $category2->sort_order);
    }

    /** @test */
    public function it_can_get_categories_by_type()
    {
        Category::factory()->create(['type' => 'design', 'is_active' => true]);
        Category::factory()->create(['type' => 'labor', 'is_active' => true]);

        $response = $this->get(route('admin.categories.by-type', ['type' => 'design']));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_prevents_circular_parent_reference()
    {
        $parent = Category::factory()->create();
        $child = Category::factory()->create(['parent_id' => $parent->id]);

        $updateData = [
            'name_ar' => $parent->name_ar,
            'type' => $parent->type,
            'parent_id' => $child->id, // Circular reference
            'is_active' => true,
        ];

        $response = $this->put(route('admin.categories.update', $parent), $updateData);

        $response->assertSessionHasErrors(['parent_id']);
    }

    /** @test */
    public function it_prevents_self_parent_reference()
    {
        $category = Category::factory()->create();

        $updateData = [
            'name_ar' => $category->name_ar,
            'type' => $category->type,
            'parent_id' => $category->id, // Self reference
            'is_active' => true,
        ];

        $response = $this->put(route('admin.categories.update', $category), $updateData);

        $response->assertSessionHasErrors(['parent_id']);
    }
}
