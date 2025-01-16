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
}
