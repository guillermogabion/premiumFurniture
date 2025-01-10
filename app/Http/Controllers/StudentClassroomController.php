<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\StudentClassroom;

class StudentClassroomController extends Controller
{
    //

    public function getStudents(Request $request)
    {
        $students = StudentClassroom::where('classroom_id', $request->input('classroom_id'))->with('user_studentclassroom', 'classroom_studentclassroom')->get();
        return response()->json([
            'students' => $students,
        ], 201);
    }

    public function getMyRoom()
    {
        $student = StudentClassroom::where('user_id', auth()->user()->id)->with('classroom_studentclassroom')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'student' => $student,
        ], 200);
    }

    public function store(Request $request)
    {
        $class = Classroom::where('classId', $request->input('classroom_id'))->first();
        if (!$class) {
            return response()->json(['message' => 'Class ID does not exist'], 409);
        }
        $isEnrolled = StudentClassroom::where('user_id', auth()->user()->id)
            ->where('classroom_id', $class->id)
            ->exists();
        if ($isEnrolled) {
            return response()->json(['message' => 'You are already enrolled in this class'], 409);
        }
        StudentClassroom::create([
            'user_id' => auth()->id(),
            'classroom_id' => $class->id,
        ]);
        return response()->json([
            'message' => 'Class enrollment submitted successfully!',
        ], 201);
    }





    public function delete($id)
    {
         $students = Classroom::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Class enrollment deleted successfully!',
            'students' => $students,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:granted,declined,pending'
        ]);

        $students = StudentClassroom::findOrFail($id);

        if ($students->status === $request->input('status')) {
            return response()->json([
                'message' => 'No changes were made, the status is already ' . $students->status,
                'students' => $students,
            ], 200);
        }

        $students->status = $request->input('status');
        $students->save();

        return response()->json([
            'message' => 'Class enrollment updated successfully!',
            'students' => $students,
        ], 200);
    }
}
