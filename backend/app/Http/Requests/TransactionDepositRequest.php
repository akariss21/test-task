<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="TransactionDepositRequest",
 *     type="object",
 *     required={"amount"},
 *     @OA\Property(
 *         property="amount",
 *         type="number",
 *         format="float",
 *         minimum=0.01,
 *         example=100.50,
 *         description="Amount to deposit to the balance"
 *     )
 * )
 */
class TransactionDepositRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
        ];
    }
}
