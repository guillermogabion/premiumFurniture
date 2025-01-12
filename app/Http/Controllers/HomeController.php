<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Details;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Room;
use App\Models\Order;
use App\Models\User;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $profile = Details::find(auth()->user()->id);
        $search = $request->input('search');
        $categoryFilter = $request->input('category');

        $items = Product::select(
            'products.id',
            'products.name',
            'products.description',
            'products.category',
            'products.images',
            'products.price',
            'products.user_id',
            'products.created_at',
            'products.status',
            \DB::raw('(SELECT COUNT(*) FROM ratings WHERE products.id = ratings.product_id) AS ratings_count'),
            \DB::raw('IFNULL(AVG(ratings.rating), 0) AS average_rating')
        )
            ->leftJoin('ratings', 'ratings.product_id', '=', 'products.id')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('products.name', 'like', '%' . $search . '%')
                        ->orWhere('products.description', 'like', '%' . $search . '%')
                        ->orWhere('products.category', 'like', '%' . $search . '%');
                });
            })
            ->when($categoryFilter, function ($query, $categoryFilter) {
                return $query->where('products.category', '=', $categoryFilter);
            })
            ->groupBy(
                'products.id',
                'products.name',
                'products.description',
                'products.category',
                'products.images',
                'products.price',
                'products.user_id',
                'products.created_at',
                'products.status'
            )
            ->with('ratings.user', 'user.gcash')
            ->orderBy('products.created_at', 'desc')
            ->get();
        foreach ($items as $item) {
            $item->images = json_decode($item->images, true);
        }

        // Fetch inbox and cart details
        $inbox = Room::where('user_id', auth()->user()->id)->get();
        $cart = Cart::with(['product.user.gcash'])->where('user_id', auth()->user()->id)->get();
        $category = Category::all(); // Get categories for the dropdown
        $total_user = User::count();

        $orders = Order::with('user')
            ->where('user_id', auth()->user()->id)
            ->get();

        $orders->each(function ($order) {
            $productIds = json_decode($order->product_ids, true);

            if (is_array($productIds) && count($productIds) > 0) {
                // Extract product IDs from the array
                $productIdsArray = array_map(function ($item) {
                    return $item['product_id']; // Get the 'product_id' from each item in 'product_ids'
                }, $productIds);

                // Get products based on extracted product_ids
                $products = Product::whereIn('id', $productIdsArray)->get();
                $order->products = $products;
            } else {
                $order->products = collect();
            }
        });
        $total_vendor = User::where('role', 'vendor')->count();
        $total_products = Product::where('status', 'active')->count();
        $total_order = Order::count();

        $my_total_products = Product::where('status', 'active')->where('user_id', auth()->user()->id)->count();


        $toPayCount = Order::where('status', 'to_pay')->count();
        $preparingCount = Order::where('status', 'preparing')->count();
        $toShipCount = Order::where('status', 'to_ship')->count();
        $shippingCount = Order::where('status', 'shipping')->count();
        $receivedCount = Order::where('status', 'received')->count();
        $cancelledCount = Order::where('status', 'cancelled')->count();





        return view('pages.home', [
            'profile' => $profile,
            'items' => $items,
            'cart' => $cart,
            'inbox2' => $inbox,
            'orders' => $orders,
            'category' => $category,
            'total_user' => $total_user,
            'total_vendor' => $total_vendor,
            'total_products' => $total_products,
            'total_order' => $total_order,
            'my_total_products' => $total_products,
            'my_total_order' => $total_order,

            'toPayCount' => $toPayCount,
            'preparingCount' => $preparingCount,
            'toShipCount' => $toShipCount,
            'shippingCount' => $shippingCount,
            'receivedCount' => $receivedCount,
            'cancelledCount' => $cancelledCount
        ]);
    }
}
