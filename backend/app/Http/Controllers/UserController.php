<?php

namespace App\Http\Controllers;

use App\Http\Requests\BecomeSellerRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/become-seller",
     *     summary="Become a seller",
     *     description="Changes the current user's role to 'seller'. If the user is already a seller, returns a message indicating this.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Response message",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are now a seller or already a seller")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/user/role",
     *     summary="Get current user's role",
     *     description="Returns the user's role (buyer or seller)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User role",
     *         @OA\JsonContent(ref="#/components/schemas/RoleResource")
     *     )
     * )
     */
    public function role()
    {
        return new RoleResource(auth()->user());
    }
}
