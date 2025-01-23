<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        $items = Product::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate();
        $user = Product::find(auth()->user()->id);
        $category = Category::get();
        return view('pages.products', ['test' => $user, 'headers' => $table_header, 'items' => $items, 'search' => $search, 'category' => $category]);
    }
    public function showAll(Request $request)
    {
        $search = $request->input('search');
        $items = Product::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->get();
        $user = Product::find(auth()->user()->id);
        $category = Category::get();
        return view('pages.products', ['test' => $user, 'headers' => $table_header, 'items' => $items, 'search' => $search, 'category' => $category]);
    }

    public function addProduct(Request $request)
    {
        $userId = auth()->user()->id;
        $cacheKey = "addProduct_{$userId}";

        if (\Cache::has($cacheKey)) {
            return redirect()->back()->with('error', 'You have already submitted this request. Please wait a moment.');
        }

        \Cache::put($cacheKey, true, 5);

        $request->validate([
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'sampleImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->input('name');
        $product->category = $request->input('category');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->status = 'active'; // Set default status
        $product->user_id = $userId;

        if ($request->hasFile('images')) {
            $imageNames = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->move(public_path('product'), $imageName);
                $imageNames[] = $imageName;
            }
            $product->images = json_encode($imageNames);
        }

        $product->save();

        return redirect()->route('products')->with('success', 'Product added successfully.');
    }


    public function update(Request $request)
    {
        $order = Order::whereJsonContains('product_ids', [['product_id' => $request->id]])->exists();
        if ($order) {

            $product = Product::findOrFail($request->id);

            $request->validate([
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'sampleImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $product->name = $request->input('name');
            $product->category = $request->input('category');
            $product->price = $request->input('price');
            $product->description = $request->input('description');


            if ($request->hasFile('images')) {
                $oldImages = json_decode($product->images, true);
                foreach ($oldImages as $oldImage) {
                    $oldImagePath = public_path('product/' . $oldImage);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $imageNames = [];
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                    $image->move(public_path('product'), $imageName);
                    $imageNames[] = $imageName;
                }
                $product->images = json_encode($imageNames);
            }

            $product->save();

            return redirect()->route('products')->with('success', 'Product updated successfully.');
        } else {
            return response()->json(['error' => 'Order found for the given product. Not Editable this time'], 404);
        }
    }
}
