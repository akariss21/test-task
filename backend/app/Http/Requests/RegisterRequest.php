<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     title="RegisterRequest",
 *     description="User registration request",
 *     type="object",
 *     required={"name", "email", "password", "gender"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Ivan Ivanov"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="ivan@example.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         example="strongpassword123"
 *     ),
 *     @OA\Property(
 *         property="gender",
 *         type="string",
 *         enum={"male", "female"},
 *         example="male"
 *     )
 * )
 */
class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'gender' => 'required|in:male,female',
        ];
    }
}