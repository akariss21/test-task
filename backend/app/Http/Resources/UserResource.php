<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     title="UserResource",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Иван Иванов"),
 *         @OA\Property(property="email", type="string", format="email", example="ivan@example.com"),
 *         @OA\Property(property="gender", type="string", enum={"male", "female"}, example="male"),
 *         @OA\Property(property="balance", type="number", format="float", example=150.75),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-30T12:34:56Z")
 *     }
 * )
 */
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'balance' => $this->balance,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
