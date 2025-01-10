<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    //

    public function showAll()
    {
        $class = Classroom::where('class_instructor_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'class' => $class,
        ], 200);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'class_schedule' => 'required|string|max:255',
            'class_subject' => 'required|string|max:255',
            'class_other' => 'nullable|string|max:255',
        ]);

        // Check if a classroom with the same schedule and subject already exists
        $existingClassroom = Classroom::where('class_schedule', $validatedData['class_schedule'])
            ->where('class_subject', $validatedData['class_subject'])
            ->where('class_instructor_id', auth()->user()->id)
            ->first();

        if ($existingClassroom) {
            // Return a response indicating the classroom already exists
            return response()->json([
                'message' => 'A classroom with the same schedule and subject already exists.',
                'data' => $existingClassroom, // Optional: return the existing class info
            ], 409); // 409 Conflict status code
        }

        // Create a new classroom if no duplicates are found
        $classroom = new Classroom();
        $classroom->classId = $this->generateUniqueClassId();
        $classroom->class_schedule = $validatedData['class_schedule'];
        $classroom->class_instructor_id = auth()->user()->id;
        $classroom->class_subject = $validatedData['class_subject'];
        $classroom->class_other = $validatedData['class_other'] ?? null; // Optional field

        $classroom->save();

        return response()->json([
            'message' => 'Classroom created successfully!',
            'data' => $classroom,
        ], 201);
    }


    private function generateUniqueClassId()
    {
        // Generate random 6 letters and 9 digits
        $letters = Str::random(6); // Generates 6 random letters
        $numbers = str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT); // Generates 9 random numbers

        return $letters . $numbers;
    }
}
