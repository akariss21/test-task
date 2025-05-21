<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function index() {
        return response()->json(Product::with('category')->get());
    }
    public function store(Request $request) {
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }
    public function update(Request $request, Product $product) {
        $request->validate([
            'name' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'price' => 'sometimes|numeric|min:0'
        ]);
        $product->update($request->all());
        return response()->json($product);
    }
    public function list(Product $product) {
        return response()->json($product->only(['id', 'name', 'price', 'category_id', 'description']));
    }
    public function destroy(Product $product) {
        $product->delete();
        return response()->json(null, 204);
    }
}
