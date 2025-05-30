<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Get list of all products",
     *     @OA\Response(
     *         response=200,
     *         description="List of products",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ProductResource"))
     *     )
     * )
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return ProductResource::collection($products);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Add a new product (seller only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product added",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     ),
     *     @OA\Response(response=403, description="User is not a seller")
     * )
     */
    public function store(ProductRequest $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isSeller()) {
            return response()->json([
                'message' => 'Станьте продавцом, чтобы добавлять товары',
                'invite_to_become_seller' => true
            ], 403);
        }

        $product = Product::create([
            ...$request->validated(),
            'user_id' => $user->id,
        ]);

        return new ProductResource($product);
    }

    /**
     * @OA\Patch(
     *     path="/products/{product}",
     *     summary="Update product information",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductUpdateRequest")
     *     ),
     *     @OA\Response(response=200, description="Product updated"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update($request->validated());
        return new ProductResource($product);
    }

    /**
     * @OA\Get(
     *     path="/products/{product}",
     *     summary="Get product by ID",
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Product data", @OA\JsonContent(ref="#/components/schemas/ProductResource")),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function list(Product $product)
    {
        return new ProductResource($product->load('category'));
    }

    /**
     * @OA\Delete(
     *     path="/products/{product}",
     *     summary="Delete a product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Product deleted"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
