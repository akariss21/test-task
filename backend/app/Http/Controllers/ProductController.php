<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function index() {
        return response()->json(Product::with('category')->get());
    }
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Неавторизован'], 401);
        }

        if (!$user->isSeller()) {
            return response()->json([
                'message' => 'Станьте продавцом, чтобы добавлять товары',
                'invite_to_become_seller' => true
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'quantity'    => 'required|integer|min:0',
            // добавь остальные поля, если нужно
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'user_id' => $user->id, // связываем товар с продавцом
            'quantity'    => $request->quantity,
        ]);

        return response()->json($product, 201);
    }
    public function update(Request $request, Product $product) {
        $request->validate([
            'name' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'quantity'    => 'required|integer|min:0',
            'price' => 'sometimes|numeric|min:0'
        ]);
        $product->update($request->all());
        return response()->json($product);
    }
    public function list(Product $product) {
        return response()->json($product->only(['id', 'name', 'price', 'category_id', 'description','quantity']));
    }
    public function destroy(Product $product) {
        $product->delete();
        return response()->json(null, 204);
    }
}
