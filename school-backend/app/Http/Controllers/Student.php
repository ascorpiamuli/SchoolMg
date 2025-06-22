<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student as StudentModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\ClassStream;

class Student extends Controller
{
    public function index()
    {
        $students = StudentModel::with([
            'classStream.class',     // Get class info
            'classStream.stream',    // Get stream info
            'classStream.teacher.user', // Get class teacher info via user
            'guardians.user'         // Guardians' user details
        ])->latest()->get();

        return response()->json($students);
    }

    public function show($id)
    {
        $student = StudentModel::with([
            'classStream.class',
            'classStream.stream',
            'classStream.teacher.user',
            'guardians.user'
        ])->find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        return response()->json($student);
    }


    public function store(Request $request)
    {
        Log::info('Student enrollment request received.', $request->all());

        $validated = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'date_of_birth'  => 'required|date',
            'gender'         => 'required|in:male,female,other',
            'enrolled_at'    => 'required|date',
            'guardian_id'    => 'required|integer|exists:guardians,id',
            'class_id'       => 'required|exists:school_classes,id',
            'avatar'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'relation'       => 'required|string|max:50',
        ]);

        if ($validated->fails()) {
            Log::warning('Student validation failed.', [
                'errors' => $validated->errors(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validated->errors(),
            ], 422);
        }

        $data = $validated->validated();
        Log::info('Validated data:', $data);

        // Assign stream with fewest students
        $stream = ClassStream::withCount('students')
            ->where('class_id', $data['class_id'])
            ->orderBy('students_count', 'asc')
            ->inRandomOrder()
            ->first();

        if (!$stream) {
            Log::error('No available stream for class_id: ' . $data['class_id']);

            return response()->json([
                'status'  => 'error',
                'message' => 'No stream available for the selected class.'
            ], 400);
        }

        $data['class_stream_id'] = $stream->id;
        unset($data['class_id']);
        Log::info('Assigned to stream ID: ' . $stream->id);

        // Generate a unique admission number like ADM1000
        $prefix     = 'ADM';
        $baseNumber = 1000;

        do {
            $lastId           = optional(StudentModel::latest('id')->first())->id ?? 0;
            $candidateNumber  = $prefix . ($baseNumber + $lastId + rand(1, 5));
            $exists           = StudentModel::where('admission_number', $candidateNumber)->exists();
        } while ($exists);

        $data['admission_number'] = $candidateNumber;
        Log::info('Generated unique admission number: ' . $candidateNumber);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
            Log::info('Avatar uploaded to path: ' . $data['avatar']);
        }

        // Save student
        try {
            $student = StudentModel::create($data);
            Log::info('Student created successfully.', ['id' => $student->id]);

            // Insert into guardian_student pivot table
            DB::table('guardian_student')->insert([
                'guardian_id' => $data['guardian_id'],
                'student_id'  => $student->id,
                'relation'    => $data['relation'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            Log::info('Guardian-student relation saved.', [
                'guardian_id' => $data['guardian_id'],
                'student_id'  => $student->id,
                'relation'    => $data['relation'],
            ]);

            return response()->json([
                'message'          => 'Student enrolled successfully. Admission Number: ' . $student->admission_number,
                'admission_number' => $student->admission_number,
                'student'          => $student
            ], 201);

        } catch (\Exception $e) {
            Log::error('Student creation failed.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong during student creation.',
            ], 500);
        }
    }



    public function update(Request $request, $id)
    {
        $student = StudentModel::findOrFail($id);

        $validated = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'middle_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:Male,Female',
            'class_stream_id' => 'sometimes|exists:class_streams,id',
            'date_of_birth' => 'sometimes|date',
            'enrolled_at' => 'sometimes|date',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $data = $validated->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $student->update($data);

        return response()->json(['message' => 'Student updated', 'student' => $student]);
    }

    public function destroy($id)
    {
        $student = StudentModel::findOrFail($id);
        $student->delete();

        return response()->json(['message' => 'Student deleted']);
    }

    public function promote($id)
    {
        $student = StudentModel::findOrFail($id);

        $currentClassStream = $student->classStream;
        $nextClass = match($currentClassStream->class->name ?? '') {
            'Grade 1' => 'Grade 2',
            'Grade 2' => 'Grade 3',
            'Grade 3' => 'Grade 4',
            'Grade 4' => 'Grade 5',
            'Grade 5' => 'Grade 6',
            'Grade 6' => 'JSS 1',
            'JSS 1' => 'JSS 2',
            'JSS 2' => 'JSS 3',
            default => 'Graduated',
        };

        $newClassStream = ClassStream::whereHas('class', fn($q) => $q->where('name', $nextClass))
            ->where('stream_id', $currentClassStream->stream_id)
            ->first();

        if (!$newClassStream) {
            return response()->json(['message' => 'Next class not available'], 404);
        }

        $student->update(['class_stream_id' => $newClassStream->id]);

        return response()->json(['message' => 'Student promoted', 'student' => $student]);
    }

    public function bulkUpload(Request $request)
    {
        $students = $request->input('students');

        if (!is_array($students)) {
            return response()->json(['message' => 'Invalid format'], 422);
        }

        $inserted = [];

        foreach ($students as $entry) {
            if (!isset($entry['first_name'], $entry['last_name'], $entry['gender'], $entry['class_stream_id'])) {
                continue;
            }

            $student = StudentModel::create([
                'first_name' => $entry['first_name'],
                'middle_name' => $entry['middle_name'] ?? null,
                'last_name' => $entry['last_name'],
                'gender' => $entry['gender'],
                'class_stream_id' => $entry['class_stream_id'],
                'enrolled_at' => $entry['enrolled_at'] ?? now(),
                'date_of_birth' => $entry['date_of_birth'] ?? null,
            ]);

            $inserted[] = $student;
        }

        return response()->json([
            'message' => count($inserted) . ' students enrolled',
            'students' => $inserted,
        ]);
    }
}
