<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Дістаємо всі позиції в корзині поточного користувача
        $userId = $request->session()->get('user_id');
        $items  = CartItem::with('product')
                  ->where('user_id', $userId)
                  ->get();

        $total = $items->sum(fn($i) => $i->product->price * $i->quantity);

        return view('checkout.index', compact('items','total'));
    }

    public function process(Request $request)
    {
        
        $userId = $request->session()->get('user_id');
        CartItem::where('user_id', $userId)->delete();

        return redirect()->route('catalog')
                         ->with('success', 'Дякуємо! Ваше замовлення прийнято.');
    }
}
