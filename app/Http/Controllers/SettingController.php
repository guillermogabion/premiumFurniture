<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    //

    public function index(Request $request)
    {
        $table_header = [
            'Type',
            'Image',
            'Text',
            'Subtext',
            'Action'
        ];
        $items = Setting::orderBy('created_at', 'desc')
            ->paginate(10);

        $user = Setting::find(auth()->user()->id);


        return view('pages.setting', ['headers' => $table_header, 'items' => $items]);
    }
    public function addOns(Request $request)
    {


        // Create a new user instance
        $user = new Setting();
        $user->type = $request->input('type');
        $user->message = $request->input('message');
        $user->submessage = $request->input('submessage');

        // Handle profile picture upload
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('upload-image'), $imageName);
            $user->image = $imageName;
        }

        // Save the user to the database
        $user->save();

        // Redirect to login with success message
        return redirect()->route('setting')->with('success', 'Add Item Success!');
    }
    public function editAddOns(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'id' => 'required|exists:settings,id',  // Make sure the id exists in the database
            'type' => 'required|string|max:255',    // Type should be a string, max length 255
            'message' => 'required|string',         // Message should be a string, required
            'submessage' => 'nullable|string',      // Submessage is optional but should be a string if provided
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Image validation
        ]);

        // Find the setting to be updated
        $item = Setting::findOrFail($request->input('id'));

        // Update the fields
        $item->type = $request->input('type');
        $item->message = $request->input('message');
        $item->submessage = $request->input('submessage', '');  // Default to empty string if submessage is null

        // Handle the image upload
        if ($request->hasFile('image')) {
            // Generate a unique file name and move the image to the public folder
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('upload-image'), $imageName);

            // Update the image path in the database
            $item->image = $imageName;
        }

        // Save the updated data to the database
        $item->save();

        // Redirect with a success message
        return redirect()->route('setting')->with('success', 'Add Item Success!');
    }


    public function deleteItem(Request $request)
    {
        $item = Setting::findorfail($request->input('id'));

        $item->delete();

        return redirect()->route('setting')->with('success', 'Remove Item Success!');
    }
}
