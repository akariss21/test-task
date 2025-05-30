<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CategoryResource",
 *     type="object",
 *     title="CategoryResource",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Fruits"),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-30T14:22:05Z")
 *     }
 * )
 */
class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
