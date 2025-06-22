<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'records' => 'required|array',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.status' => 'required|in:present,absent,late,excused'
        ]);

        foreach ($request->records as $record) {
            Attendance::updateOrCreate(
                ['student_id' => $record['student_id'], 'date' => $request->date],
                ['status' => $record['status']]
            );
        }

        return response()->json(['message' => 'Attendance recorded successfully.']);
    }

public function index(Request $request)
{
    $request->validate(['date' => 'required|date']);

    $attendances = Attendance::with(['student.classStream.class', 'student.classStream.stream', 'student.classStream.teacher.user'])
        ->where('date', $request->date)
        ->get();

    return response()->json($attendances);
}

}

