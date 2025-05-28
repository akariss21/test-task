<?php

namespace App\Http\Controllers;

use App\Http\Requests\BalanceRequest;
use App\Http\Resources\BalanceResource;

class BalanceController extends Controller
{
    public function show(BalanceRequest $request)
    {
        return new BalanceResource($request->user());
    }
}