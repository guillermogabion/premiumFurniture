<?php

namespace App\Http\Controllers;

use App\Models\ClassroomInbox;
use Illuminate\Http\Request;

class ClassroomInboxController extends Controller
{

    public function index($classroomId)
    {
        try {
            // Fetch inbox messages for the given classroom ID
            $inbox = ClassroomInbox::where('classroom_id', $classroomId)->with('class_inbox')->get();

            // Check if there are any messages
            if ($inbox->isEmpty()) {
                return response()->json([
                    'message' => 'No messages found for this classroom.',
                    'inbox' => []
                ], 200);
            }

            // Return the inbox data
            return response()->json([
                'message' => 'Messages retrieved successfully.',
                'inbox' => $inbox
            ], 200);
        } catch (\Exception $e) {
            // Return error response in case of any exceptions
            return response()->json([
                'error' => 'Failed to retrieve messages.',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function send(Request $request)
    {


        $message = new ClassroomInbox();
        $message->user_id = auth()->user()->id;
        $message->classroom_id = $request->input('classroom_id');
        $message->message_text = $request->input('message_text');
        $message->deadline = $request->input('deadline');

        // Set the type, defaulting to 'text' if not provided
        $message->type = $request->input('type', 'text'); // Default to 'text'

        // Check if a file is uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('messagefile'), $fileName);

            $message->file = $fileName;
        } else {
            $message->file = null; // This line is optional, as file will already be null
        }

        // Save the message to the database
        $message->save();
        return response()->json([
            'message' => $message,
        ], 200);
    }
}
