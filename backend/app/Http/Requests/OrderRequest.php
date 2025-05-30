<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="OrderRequest",
 *     title="OrderRequest",
 *     description="Request to create an order",
 *     type="object",
 *     required={"customer_name", "order_date", "products"},
 *     @OA\Property(
 *         property="customer_name",
 *         type="string",
 *         example="Ivan Ivanov"
 *     ),
 *     @OA\Property(
 *         property="order_date",
 *         type="string",
 *         format="date",
 *         example="2025-05-30"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"new", "completed"},
 *         example="new"
 *     ),
 *     @OA\Property(
 *         property="comment",
 *         type="string",
 *         example="Please deliver by evening"
 *     ),
 *     @OA\Property(
 *         property="products",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"id", "quantity"},
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="quantity", type="integer", example=2)
 *         )
 *     )
 * )
 */
class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Разрешаем доступ всем авторизованным
    }

    public function rules()
    {
        return [
            'customer_name' => 'required|string|max:255',
            'order_date' => 'required|date',
            'status' => 'nullable|string|in:new,completed',
            'comment' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'products.*.id.exists' => 'Продукт с указанным ID не найден.',
            'products.*.quantity.min' => 'Минимальное количество — 1.',
        ];
    }
}
