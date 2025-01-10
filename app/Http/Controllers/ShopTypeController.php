<?php

namespace App\Http\Controllers;

use App\Models\ShopType;
use Illuminate\Http\Request;

class ShopTypeController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'ID',
            'Name',
            'Status',
            'Action'
        ];
        $items = ShopType::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            // ->where('role', 'student')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $user = ShopType::find(auth()->user()->id);
        return view('pages.shoptype', ['test' => $user, 'headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function newType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:shop_types,name',
        ]);

        $item = new ShopType();
        $item->name = $request->input('name');
        $item->save();

        return redirect()->route('shop_type')->with('success', 'Add new item successful.');
    }


    public function updateType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:shop_types,name',
        ]);
        $item = ShopType::findorfail($request->input('id'));

        $item->name = $request->input('name');
        $item->save();
        return redirect()->route('shop_type')->with('success', 'Edit item successful.');
    }
}
