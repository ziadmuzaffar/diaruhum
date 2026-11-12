<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'slug' => $this->slug,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'icon' => $this->icon,
            'image' => $this->image ? Storage::url($this->image) : null,
            'type' => $this->type,
            'type_label' => $this->getTypeLabel(),
            'parent_id' => $this->parent_id,
            'parent' => $this->whenLoaded('parent', function () {
                return new CategoryResource($this->parent);
            }),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
            'children_count' => $this->children_count ?? $this->children()->count(),
            'services_count' => $this->services_count ?? $this->services()->count(),
            'products_count' => $this->products_count ?? $this->products()->count(),
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'formatted_created_at' => $this->created_at?->format('Y-m-d H:i'),
            'formatted_updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
    }

    /**
     * Get the type label in Arabic.
     */
    private function getTypeLabel(): string
    {
        return match ($this->type) {
            'design' => 'تصميم',
            'labor' => 'عمالة',
            'materials' => 'مواد',
            'logistics' => 'لوجستيات',
            'realestate' => 'عقارات',
            default => $this->type,
        };
    }
}
