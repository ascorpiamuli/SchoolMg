<?php

namespace App\Http\Controllers;

use App\Models\TeacherAttendance;
use Illuminate\Http\Request;

class TeacherAttendanceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'records' => 'required|array',
            'records.*.teacher_id' => 'required|exists:teachers,id',
            'records.*.status' => 'required|in:present,absent,late,on_leave,remote',
            'records.*.device_fingerprint' => 'nullable|string|max:255',
            'records.*.location' => 'nullable|array',
            'records.*.ip_address' => 'nullable|ip'
        ]);

        foreach ($validated['records'] as $record) {
            TeacherAttendance::updateOrCreate(
                [
                    'teacher_id' => $record['teacher_id'],
                    'date' => $validated['date']
                ],
                [
                    'status' => $record['status'],
                    'device_fingerprint' => $record['device_fingerprint'] ?? null,
                    'ip_address' => $record['ip_address'] ?? null,
                    'location' => json_encode($record['location'] ?? [])
                ]
            );
        }

        return response()->json(['message' => 'Teacher attendance recorded successfully.']);
    }


    // Get attendance for a specific date
    public function index(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date'
        ]);

        $attendance = TeacherAttendance::with('teacher.user') // assuming teacher has user info
            ->where('date', $validated['date'])
            ->get();

        return response()->json($attendance);
    }
}

