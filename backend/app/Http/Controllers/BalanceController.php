<?php

namespace App\Http\Controllers;

use App\Http\Requests\BalanceRequest;
use App\Http\Resources\BalanceResource;

class BalanceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/balance",
     *     summary="Get current user's balance",
     *     tags={"Balance"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User balance returned successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BalanceResource")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show(BalanceRequest $request)
    {
        return new BalanceResource($request->user());
    }
}