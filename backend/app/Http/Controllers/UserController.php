<?php

namespace App\Http\Controllers;

use App\Http\Requests\BecomeSellerRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function becomeSeller(BecomeSellerRequest $request)
    {
        $user = $request->user();

        if ($user->role === 'seller') {
            return response()->json(['message' => 'Вы уже продавец']);
        }

        $user->role = 'seller';
        $user->save();

        return response()->json(['message' => 'Теперь вы продавец']);
    }

    public function role()
    {
        return new RoleResource(auth()->user());
    }
}
