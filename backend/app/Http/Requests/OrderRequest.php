<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'status' => 'nullable|string|in:new,pending,completed,cancelled',
            'comment' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
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
