<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
