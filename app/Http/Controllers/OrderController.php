<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;


use Illuminate\Http\Request;

class OrderController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'ID',
            'Name',
            'Buyer',
            'Address',
            'Contact',
            'Quantity',
            'Date',
            'Status',
        ];
        $items = Order::when($search, function ($query, $search) {
            return $query->where('orderId', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $items->each(function ($order) {
            $productIds = json_decode($order->product_ids, true);

            if (is_array($productIds) && count($productIds) > 0) {
                // Extract product IDs from the array
                $productIdsArray = array_map(function ($item) {
                    return $item['product_id']; // Get the 'product_id' from each item in 'product_ids'
                }, $productIds);

                // Get products based on extracted product_ids and filter by authenticated user
                $products = Product::whereIn('id', $productIdsArray)
                    ->where('user_id', auth()->user()->id) // Filter products by authenticated user's ID
                    ->get();

                $order->products = $products;
            } else {
                $order->products = collect(); // Set empty collection if no products
            }
        });



        return view('pages.orders', ['headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function addOrder(Request $request)
    {
        $userId = auth()->user()->id;
        $cacheKey = "addOrder_{$userId}";

        if (\Cache::has($cacheKey)) {
            return response()->json(['success' => false, 'message' => 'You are submitting too quickly. Please wait.']);
        }

        \Cache::put($cacheKey, true, 5);

        $request->validate([
            'order_id' => 'required|string|max:255',
            'orders' => 'required|json',
            'payment_mode' => 'required|string|in:downpayment,fullpayment',
            'ref_no' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $orders = json_decode($request->input('orders'), true);
        if (empty($orders)) {
            return response()->json(['success' => false, 'message' => 'Orders data is empty.']);
        }

        $paymentMode = $request->input('payment_mode');
        $totalAmount = array_reduce($orders, fn($sum, $order) => $sum + $order['total'], 0);

        $downpaymentAmount = $paymentMode === 'downpayment' ? $totalAmount / 2 : null;

        foreach ($orders as $orderData) {
            if (!isset($orderData['product_id']) || !isset($orderData['quantity']) || !isset($orderData['total'])) {
                return response()->json(['success' => false, 'message' => 'Missing required fields: product_id, quantity, or total.']);
            }
        }

        $order = Order::create([

            'quantity' => 0,
            'date' => now(),
            'total' => $totalAmount,
            'downpayment_amount' => $downpaymentAmount,
            'user_id' => $userId,
            'status' => 'to_pay',
            'ref_no' => $request->input('ref_no'),
            'payment_mode' => $paymentMode,
            'image' => $request->hasFile('image') ? $request->file('image')->store('orders', 'public') : null,
            'orderId' => $request->input('order_id'),
            'product_ids' => json_encode($orders),
        ]);

        $cartIds = array_column($orders, 'cart_id');
        Cart::whereIn('id', $cartIds)->delete();

        return response()->json(['success' => true, 'message' => 'Order placed successfully!', 'order_id' => $order->id]);
    }








    public function updateStatus(Request $request)
    {

        $order = Order::findOrFail($request->id);
        $order->status = $request->input('status');
        $order->save();

        return redirect()->route('orders')->with('success', 'Order status updated successfully');
    }
}
