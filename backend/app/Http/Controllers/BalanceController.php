<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class BalanceController extends Controller
{
    public function showBalance(Request $request)
    {
        $user = $request->user();
        return response()->json(['balance' => $user->balance]);
    }
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        DB::transaction(function () use ($request) {
            $user = $request->user();
            $user->balance += $request->amount;
            $user->save();

            $user->transactions()->create([
                'amount' => $request->amount,
                'type' => Transaction::TYPE_DEPOSIT,
                'description' => 'Пополнение баланса'
            ]);
        });

        return response()->json(['message' => 'Balance updated']);
    }

    public function purchase(Request $request, Product $product)
    {
        if ($request->user()->isSeller()) {
            abort(403, 'Sellers cannot purchase products');
        }

        DB::transaction(function () use ($request, $product) {
            $buyer = $request->user();
            $seller = $product->seller;

            if ($buyer->balance < $product->price) {
                abort(400, 'Insufficient funds');
            }

            // Списание у покупателя
            $buyer->balance -= $product->price;
            $buyer->save();

            // Зачисление продавцу (90%)
            $seller->balance += $product->seller_profit;
            $seller->save();

            // Записи транзакций
            $buyer->transactions()->create([
                'amount' => -$product->price,
                'type' => Transaction::TYPE_PURCHASE,
                'description' => "Покупка: {$product->name}"
            ]);

            $seller->transactions()->create([
                'amount' => $product->seller_profit,
                'type' => Transaction::TYPE_SELLER_INCOME,
                'description' => "Продажа: {$product->name}"
            ]);
        });

        return response()->json(['message' => 'Purchase successful']);
    }
}
