<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    //
    public function addRate(Request $request)
    {
        // Validate the request input
        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5', // Ensure rating is between 1 and 5
            'comment' => 'nullable|string|max:255', // Optional comment
            'product_id' => 'required|exists:products,id', // Ensure product exists
        ]);

        // Create the rating
        $rating = Rating::create([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'user_id' => auth()->id(), // Assuming user is logged in
            'product_id' => $validated['product_id'],
        ]);

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Rating added successfully!',
            'data' => $rating,
        ]);
    }
}
