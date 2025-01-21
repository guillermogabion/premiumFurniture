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


        $user = new Setting();
        $user->type = $request->input('type');
        $user->message = $request->input('message');
        $user->submessage = $request->input('submessage');

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('upload-image'), $imageName);
            $user->image = $imageName;
        }

        $user->save();

        return redirect()->route('setting')->with('success', 'Add Item Success!');
    }
    public function editAddOns(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:settings,id',
            'type' => 'required|string|max:255',
            'message' => 'required|string',
            'submessage' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Find the setting to be updated
        $item = Setting::findOrFail($request->input('id'));

        // Update the fields
        $item->type = $request->input('type');
        $item->message = $request->input('message');
        $item->submessage = $request->input('submessage', '');

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('upload-image'), $imageName);

            $item->image = $imageName;
        }

        $item->save();

        return redirect()->route('setting')->with('success', 'Add Item Success!');
    }


    public function deleteItem(Request $request)
    {
        $item = Setting::findorfail($request->input('id'));

        $item->delete();

        return redirect()->route('setting')->with('success', 'Remove Item Success!');
    }
}
