<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function becomeSeller()
    {
        $user = Auth::user();

        if ($user->role === 'seller') {
            return response()->json(['message' => 'Вы уже продавец']);
        }

        $user->role = 'seller';
        $user->save();

        return response()->json(['message' => 'Теперь вы продавец']);
    }

    public function role()
    {
        return response()->json(['role' => Auth::user()->role]);
    }
}
