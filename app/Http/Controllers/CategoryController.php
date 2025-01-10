<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'ID',
            'Name',
            'Status',
            'Action'
        ];
        $items = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            // ->where('role', 'student')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $user = Category::find(auth()->user()->id);
        return view('pages.category', ['test' => $user, 'headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function newCategory(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
        $item = new Category();
        $item->name = $request->input('name');
        $item->save();

        return redirect()->route('category')->with('success', 'Add new item successful.');
    }

    public function updateCategory(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
        $item = Category::findorfail($request->input('id'));

        $item->name = $request->input('name');
        $item->save();
        return redirect()->route('category')->with('success', 'Edit item successful.');
    }
}
