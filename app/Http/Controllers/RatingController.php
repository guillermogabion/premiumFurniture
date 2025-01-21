<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    //
    public function addRate(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:255',
            'product_id' => 'required|exists:products,id',
        ]);

        $rating = Rating::create([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating added successfully!',
            'data' => $rating,
        ]);
    }
}
