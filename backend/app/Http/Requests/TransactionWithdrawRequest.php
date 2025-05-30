<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="TransactionWithdrawRequest",
 *     type="object",
 *     required={"amount"},
 *     @OA\Property(
 *         property="amount",
 *         type="number",
 *         format="float",
 *         example=100.50,
 *         description="Amount to withdraw from the account"
 *     )
 * )
 */
class TransactionWithdrawRequest extends FormRequest
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
