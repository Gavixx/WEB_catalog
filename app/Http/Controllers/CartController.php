<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Показати корзину
    public function index(Request $request)
    {
        $userId = $request->session()->get('user_id');

        $items = CartItem::with('product')
            ->where('user_id', $userId)
            ->get();

        $total = $items->sum(fn($i)=> $i->product->price * $i->quantity);

        return view('cart.index', compact('items','total'));
    }

    // Додати в корзину або збільшити кількість
    public function add(Request $request, Product $product)
    {
        $userId = $request->session()->get('user_id');

        $item = CartItem::firstOrNew([
            'user_id'    => $userId,
            'product_id' => $product->id,
        ]);
        $item->quantity += 1;
        $item->save();

        return redirect()->route('cart.index')
                         ->with('success','Товар додано в корзину');
    }

    // Оновити кількість (PUT)
    public function update(Request $request, CartItem $cartItem)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update($data);

        return back()->with('success','Кількість оновлено');
    }

    // Видалити позицію
    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success','Товар видалено з корзини');
    }

    // Очистити всю корзину
    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->session()->get('user_id'))
                ->delete();
        return back()->with('success','Корзина очищена');
    }
}
