<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomMessages;
use Illuminate\Http\Request;

class RoomMessagesController extends Controller
{
    //

    public function index(Request $request, $id)
    {



        $messages = RoomMessages::with('user', 'seller')->where('room_id', $id)->orderBy('created_at', 'desc')->get();
        $chat = Room::with('user', 'user_customer')->find($id);


        return view('pages.messages', ['items' => $messages, 'roomId' => $id, 'chat' => $chat]);
    }

    public function addMessage(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|integer',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $room = Room::where('user_id', auth()->user()->id)
            ->where('seller_id', $request->input('seller_id'))
            ->first();

        if (!$room) {
            $room = new Room();
            $room->user_id = auth()->user()->id;
            $room->seller_id = $request->input('seller_id');
            $room->save();
        }

        // Save the message
        $message = new RoomMessages();
        $message->sender_id = auth()->user()->id;
        $message->room_id = $room->id; // Use the existing or newly created room ID
        $message->message = $request->input('message');
        $message->isRead =  auth()->user()->id;

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('messages'), $imageName);
            $message->image = $imageName;
        }

        $message->save();
        return redirect()->route('home')->with('success', 'Message sent successfully.');
    }



    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'room_id' => 'required|integer', // Ensure the room_id is provided
        ]);

        // Store the message in the database with the associated room_id
        $message = new RoomMessages();
        $message->sender_id = auth()->user()->id;
        $message->room_id =  $request->input('room_id'); // Use the existing or newly created room ID
        $message->message = $request->input('message');
        $message->isRead =  auth()->user()->id;

        $message->save();

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function fetchMessages($roomId)
    {
        $items = RoomMessages::where('room_id', $roomId)->orderBy('created_at', 'desc')->get();
        $profile = auth()->user()->profile; // Adjust as needed

        return view('partials.messages', compact('items', 'profile', 'roomId'))->render();
    }


    public function readMessage(Request $request)
    {
        $message = RoomMessages::where('isRead', '!=', auth()->user()->id)
            ->where('room_id', $request->input('id'))
            ->latest()
            ->first();

        if ($message) {
            // Update the message to mark it as read (assuming 0 means read here)
            $message->update(['isRead' => 0]);

            // Return a successful response
            return response()->json(['message' => 'Message opened successfully']);
        }

        return response()->json(['message' => 'No unread message found in this room'], 404);
    }
}
