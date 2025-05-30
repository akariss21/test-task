<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="BalanceRequest",
 *     description="Empty request to get balance",
 *     type="object"
 * )
 */
class BalanceRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Авторизованные пользователи допускаются
    }

    public function rules()
    {
        return []; // Пока правил нет, но можно добавить позже (например, проверку прав)
    }
}
