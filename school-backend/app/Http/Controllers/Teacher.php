<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher as TeacherModel;
class Teacher extends Controller
{
    public function index()
    {
        $teachers = TeacherModel::with([
            'user',
            'attendances',
            'class_stream.class',
            'class_stream.stream',
            'subjectsTeaching',
            'specializedSubjects',
        ])->get();
        return response()->json($teachers);
    }

    public function show($id)
    {
        // Logic to retrieve and return a specific teacher by ID
    }

    public function store(Request $request)
    {
        // Logic to create a new teacher
    }

    public function update(Request $request, $id)
    {
        // Logic to update an existing teacher
    }

    public function destroy($id)
    {
        // Logic to delete a teacher
    }
}
