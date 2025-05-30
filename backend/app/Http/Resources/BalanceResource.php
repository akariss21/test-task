<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="BalanceResource",
 *     type="object",
 *     @OA\Property(
 *         property="balance",
 *         type="number",
 *         format="float",
 *         example=100.50,
 *         description="Current user balance"
 *     )
 * )
 */
class BalanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'balance' => $this->balance,
        ];
    }
}