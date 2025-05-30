<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ProductUpdateRequest",
 *     title="ProductUpdateRequest",
 *     description="Request to update a product",
 *     type="object",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Green apple",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         example="Fresh green apples"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         minimum=0.01,
 *         example=18.50,
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         example=3,
 *         nullable=true,
 *         description="ID of an existing category"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="integer",
 *         minimum=0,
 *         example=50
 *     )
 * )
 */
class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price'       => 'sometimes|numeric|min:0.01',
            'category_id' => 'sometimes|exists:categories,id',
            'quantity'    => 'required|integer|min:0',
        ];
    }
}
