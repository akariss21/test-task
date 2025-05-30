<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ProductRequest",
 *     title="ProductRequest",
 *     description="Request to create a new product",
 *     type="object",
 *     required={"name", "price", "category_id", "quantity"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Apple"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         example="Fresh green apple"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         minimum=0.01,
 *         example=19.99
 *     ),
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         example=3,
 *         description="ID of an existing category"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="integer",
 *         minimum=0,
 *         example=100
 *     )
 * )
 */
class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'quantity'    => 'required|integer|min:0',
        ];
    }
}
