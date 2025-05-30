<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="BecomeSellerRequest",
 *     description="Request to switch to seller role. Request body is empty.",
 *     type="object"
 * )
 */
class BecomeSellerRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Можно, например, запретить доступ неавторизованным:
        return auth()->check();
    }

    public function rules(): array
    {
        // Пока не требует данных, но может пригодиться для будущей логики.
        return [];
    }
}
