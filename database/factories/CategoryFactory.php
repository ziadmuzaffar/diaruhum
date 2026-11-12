<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nameAr = $this->faker->randomElement([
            'تصميم داخلي',
            'تصميم خارجي',
            'أعمال السباكة',
            'أعمال الكهرباء',
            'أعمال الدهان',
            'أعمال البلاط',
            'أعمال النجارة',
            'أعمال الحدادة',
            'مواد البناء',
            'أدوات كهربائية',
            'خدمات التوصيل',
            'خدمات النقل',
            'عقارات سكنية',
            'عقارات تجارية',
            'عقارات إدارية'
        ]);

        $nameEn = $this->faker->randomElement([
            'Interior Design',
            'Exterior Design',
            'Plumbing Works',
            'Electrical Works',
            'Painting Works',
            'Tiling Works',
            'Carpentry Works',
            'Metalwork',
            'Building Materials',
            'Electrical Tools',
            'Delivery Services',
            'Transportation Services',
            'Residential Properties',
            'Commercial Properties',
            'Administrative Properties'
        ]);

        $slug = Str::slug($nameAr);

        return [
            'name_ar' => $nameAr,
            'name_en' => $nameEn,
            'slug' => $slug,
            'description_ar' => $this->faker->optional()->paragraph(),
            'description_en' => $this->faker->optional()->paragraph(),
            'icon' => $this->faker->optional()->randomElement([
                'fas fa-home',
                'fas fa-tools',
                'fas fa-paint-brush',
                'fas fa-hammer',
                'fas fa-wrench',
                'fas fa-bolt',
                'fas fa-truck',
                'fas fa-building'
            ]),
            'type' => $this->faker->randomElement(['design', 'labor', 'materials', 'logistics', 'realestate']),
            'parent_id' => null, // Will be set in seeder for some categories
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
            'metadata' => $this->faker->optional()->randomElement([
                ['color' => '#3B82F6'],
                ['featured' => true],
                ['priority' => 'high'],
                null
            ]),
        ];
    }

    /**
     * Indicate that the category is a parent category.
     */
    public function parent(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => null,
        ]);
    }

    /**
     * Indicate that the category is a child category.
     */
    public function child(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => Category::factory()->parent(),
        ]);
    }

    /**
     * Indicate that the category is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a category of specific type.
     */
    public function ofType(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }
}
