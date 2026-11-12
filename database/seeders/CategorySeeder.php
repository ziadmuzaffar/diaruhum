<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Design Categories
        $designParent = Category::create([
            'name_ar' => 'التصميم',
            'name_en' => 'Design',
            'slug' => 'design',
            'description_ar' => 'خدمات التصميم المعماري والداخلي',
            'description_en' => 'Architectural and interior design services',
            'icon' => 'fas fa-drafting-compass',
            'type' => 'design',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $designChildren = [
            ['name_ar' => 'التصميم الداخلي', 'name_en' => 'Interior Design', 'icon' => 'fas fa-home'],
            ['name_ar' => 'التصميم الخارجي', 'name_en' => 'Exterior Design', 'icon' => 'fas fa-building'],
            ['name_ar' => 'تصميم المناظر الطبيعية', 'name_en' => 'Landscape Design', 'icon' => 'fas fa-tree'],
            ['name_ar' => 'التصميم المعماري', 'name_en' => 'Architectural Design', 'icon' => 'fas fa-ruler-combined'],
        ];

        foreach ($designChildren as $index => $child) {
            Category::create([
                'name_ar' => $child['name_ar'],
                'name_en' => $child['name_en'],
                'slug' => Str::slug($child['name_ar']),
                'icon' => $child['icon'],
                'type' => 'design',
                'parent_id' => $designParent->id,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Labor Categories
        $laborParent = Category::create([
            'name_ar' => 'العمالة',
            'name_en' => 'Labor',
            'slug' => 'labor',
            'description_ar' => 'خدمات العمالة المتخصصة',
            'description_en' => 'Specialized labor services',
            'icon' => 'fas fa-tools',
            'type' => 'labor',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $laborChildren = [
            ['name_ar' => 'أعمال السباكة', 'name_en' => 'Plumbing Works', 'icon' => 'fas fa-wrench'],
            ['name_ar' => 'أعمال الكهرباء', 'name_en' => 'Electrical Works', 'icon' => 'fas fa-bolt'],
            ['name_ar' => 'أعمال الدهان', 'name_en' => 'Painting Works', 'icon' => 'fas fa-paint-brush'],
            ['name_ar' => 'أعمال البلاط', 'name_en' => 'Tiling Works', 'icon' => 'fas fa-th'],
            ['name_ar' => 'أعمال النجارة', 'name_en' => 'Carpentry Works', 'icon' => 'fas fa-hammer'],
            ['name_ar' => 'أعمال الحدادة', 'name_en' => 'Metalwork', 'icon' => 'fas fa-industry'],
        ];

        foreach ($laborChildren as $index => $child) {
            Category::create([
                'name_ar' => $child['name_ar'],
                'name_en' => $child['name_en'],
                'slug' => Str::slug($child['name_ar']),
                'icon' => $child['icon'],
                'type' => 'labor',
                'parent_id' => $laborParent->id,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Materials Categories
        $materialsParent = Category::create([
            'name_ar' => 'المواد',
            'name_en' => 'Materials',
            'slug' => 'materials',
            'description_ar' => 'مواد البناء والتشطيب',
            'description_en' => 'Construction and finishing materials',
            'icon' => 'fas fa-cubes',
            'type' => 'materials',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $materialsChildren = [
            ['name_ar' => 'مواد البناء', 'name_en' => 'Building Materials', 'icon' => 'fas fa-cube'],
            ['name_ar' => 'أدوات كهربائية', 'name_en' => 'Electrical Tools', 'icon' => 'fas fa-plug'],
            ['name_ar' => 'أدوات السباكة', 'name_en' => 'Plumbing Tools', 'icon' => 'fas fa-faucet'],
            ['name_ar' => 'مواد الدهان', 'name_en' => 'Paint Materials', 'icon' => 'fas fa-palette'],
            ['name_ar' => 'البلاط والسيراميك', 'name_en' => 'Tiles & Ceramics', 'icon' => 'fas fa-border-all'],
        ];

        foreach ($materialsChildren as $index => $child) {
            Category::create([
                'name_ar' => $child['name_ar'],
                'name_en' => $child['name_en'],
                'slug' => Str::slug($child['name_ar']),
                'icon' => $child['icon'],
                'type' => 'materials',
                'parent_id' => $materialsParent->id,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Logistics Categories
        $logisticsParent = Category::create([
            'name_ar' => 'اللوجستيات',
            'name_en' => 'Logistics',
            'slug' => 'logistics',
            'description_ar' => 'خدمات النقل والتوصيل',
            'description_en' => 'Transportation and delivery services',
            'icon' => 'fas fa-truck',
            'type' => 'logistics',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $logisticsChildren = [
            ['name_ar' => 'خدمات التوصيل', 'name_en' => 'Delivery Services', 'icon' => 'fas fa-shipping-fast'],
            ['name_ar' => 'خدمات النقل', 'name_en' => 'Transportation Services', 'icon' => 'fas fa-truck-moving'],
            ['name_ar' => 'نقل المواد الثقيلة', 'name_en' => 'Heavy Materials Transport', 'icon' => 'fas fa-dolly'],
            ['name_ar' => 'خدمات التخزين', 'name_en' => 'Storage Services', 'icon' => 'fas fa-warehouse'],
        ];

        foreach ($logisticsChildren as $index => $child) {
            Category::create([
                'name_ar' => $child['name_ar'],
                'name_en' => $child['name_en'],
                'slug' => Str::slug($child['name_ar']),
                'icon' => $child['icon'],
                'type' => 'logistics',
                'parent_id' => $logisticsParent->id,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Real Estate Categories
        $realestateParent = Category::create([
            'name_ar' => 'العقارات',
            'name_en' => 'Real Estate',
            'slug' => 'realestate',
            'description_ar' => 'خدمات العقارات والاستثمار العقاري',
            'description_en' => 'Real estate and property investment services',
            'icon' => 'fas fa-building',
            'type' => 'realestate',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $realestateChildren = [
            ['name_ar' => 'عقارات سكنية', 'name_en' => 'Residential Properties', 'icon' => 'fas fa-home'],
            ['name_ar' => 'عقارات تجارية', 'name_en' => 'Commercial Properties', 'icon' => 'fas fa-store'],
            ['name_ar' => 'عقارات إدارية', 'name_en' => 'Administrative Properties', 'icon' => 'fas fa-building'],
            ['name_ar' => 'أراضي', 'name_en' => 'Land', 'icon' => 'fas fa-map'],
            ['name_ar' => 'عقارات استثمارية', 'name_en' => 'Investment Properties', 'icon' => 'fas fa-chart-line'],
        ];

        foreach ($realestateChildren as $index => $child) {
            Category::create([
                'name_ar' => $child['name_ar'],
                'name_en' => $child['name_en'],
                'slug' => Str::slug($child['name_ar']),
                'icon' => $child['icon'],
                'type' => 'realestate',
                'parent_id' => $realestateParent->id,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
