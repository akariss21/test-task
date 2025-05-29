<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BalanceController extends Controller
{
    public function show()
    {
        return response()->json(['balance' => auth()->user()->balance]);
    }
}