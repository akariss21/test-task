<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return ProductResource::collection($products);
    }

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

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update($request->validated());
        return new ProductResource($product);
    }

    public function list(Product $product)
    {
        return new ProductResource($product->load('category'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
