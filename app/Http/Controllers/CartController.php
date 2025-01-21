<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'ID',
            'Image',
            'Name',
            'Price',
            'Status',
            'Action'
        ];
        $items = Cart::with('users')->when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.products', ['headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function addCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $recentCartEntry = Cart::where('user_id', auth()->user()->id)
            ->where('product_id', $request->input('product_id'))
            ->where('created_at', '>=', now()->subSeconds(5))
            
            ->first();

        if ($recentCartEntry) {
            return redirect()->route('home')->with('error', 'Item already added to the cart. Please wait.');
        }

        $cart = new Cart();
        $cart->user_id = auth()->user()->id;
        $cart->product_id = $request->input('product_id');
        $cart->quantity = $request->input('quantity');
        $cart->save();

        return redirect()->route('home')->with('success', 'Add Cart successful.');
    }
}
