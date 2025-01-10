<?php

namespace App\Http\Controllers;

use App\Models\Details;
use App\Models\Organization;
use Illuminate\Http\Request;

class DetailsController extends Controller
{
    //

    public function index()
    {
        $profile = Details::findorfail(auth()->user()->id);

        return view('components.header', compact('profile'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'organization_id' => 'nullable|exists:organizations,id', // Ensure organization exists
            'profile' => 'nullable|file|max:10240|mimes:jpg,jpeg,png' // Profile image is optional for updates
        ]);

        // Fetch user's details, if they exist
        $detail = Details::where('user_id', auth()->user()->id)->first();

        // Check if the organization ID exists
        // $orgId = Organization::find($request->input('organization_id'))->first();

        // If no valid organization ID is provided
        // if (!$orgId) {
        //     return response()->json(['success' => false, 'message' => 'Organization ID does not exist'], 400);
        // }

        // Handle profile image upload if it exists
        $fileName = null;
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('profile'), $fileName);
        }

        if ($detail) {
            // Update existing user details
            if ($fileName) {
                $detail->profile = $fileName; // Only update profile if a new one is provided
            }
            $detail->name = $request->input('name');
            $detail->role = $request->input('role');
            $detail->organization_id = $request->input('organization_id');
            $detail->save();

            return response()->json(['success' => true, 'message' => 'User details updated successfully'], 200);
        } else {
            // Create new entry for user details
            if ($fileName) {
                Details::create([
                    'name' => $request->input('name'),
                    'role' => $request->input('role'),
                    'organization_id' => $request->input('organization_id'),
                    'profile' => $fileName,
                    'user_id' => auth()->user()->id,
                ]);

                return response()->json(['success' => true, 'message' => 'User details added successfully'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Profile image is required for new entries.'], 400);
            }
        }
    }
}
