<?php

namespace App\Http\Controllers;

use App\Models\Positions;
use Illuminate\Http\Request;

class PositionsController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = ['ID', 'Position', 'Available Position', 'Status', 'Action'];

        $positions = Positions::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.positions', ['headers' => $table_header, 'positions' => $positions, 'search' => $search]);
    }

    public function store(Request $request)
    {

        $positions = new Positions();
        $positions->name = $request->input('name');
        $positions->number = $request->input('number');
        $positions->save();

        return redirect()->route('positions');
    }

    public function update(Request $request)
    {

        $positions = Positions::findOrFail($request->id);
        $positions->fill($request->all());
        $positions->save();

        return redirect()->route('positions')->with('success', 'Position updated successfully');
    }

    public function updateStatus(Request $request)
    {

        $positions = Positions::findOrFail($request->id);
        $positions->status = $request->input('status');
        $positions->save();

        return redirect()->route('positions')->with('success', 'Position status updated successfully');
    }
}
