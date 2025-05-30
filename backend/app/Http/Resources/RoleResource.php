<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RoleResource",
 *     type="object",
 *     title="RoleResource",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Ivan Ivanov"),
 *         @OA\Property(property="role", type="string", example="seller"),
 *         @OA\Property(property="is_seller", type="boolean", example=true)
 *     }
 * )
 */
class RoleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'role' => $this->role,
            'is_seller' => $this->role === 'seller',
        ];
    }
}
