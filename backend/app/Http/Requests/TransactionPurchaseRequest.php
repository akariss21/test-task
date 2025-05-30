<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="TransactionPurchaseRequest",
 *     type="object",
 *     required={"order_id"},
 *     @OA\Property(
 *         property="order_id",
 *         type="integer",
 *         example=123,
 *         description="ID of the order to purchase"
 *     )
 * )
 */
class TransactionPurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
        ];
    }
}
