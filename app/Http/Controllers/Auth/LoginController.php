<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Product;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function showLoginForm(Request $request)
    {
        $search = $request->input('search');
        $categoryFilter = $request->input('category');

        $items = Product::select(
            'products.id',
            'products.name',
            'products.description',
            'products.category',
            'products.images',
            'products.price',
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
                'products.created_at',
                'products.status'
            )
            ->orderBy('products.created_at', 'desc')
            ->get();

        foreach ($items as $item) {
            $item->images = json_decode($item->images, true);
        }


        return view('auth.login')->with('items', $items);
    }
}
