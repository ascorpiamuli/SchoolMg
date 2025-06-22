<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Guardian;
use App\Models\Accountant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        \Log::info('Login attempt', ['email' => $request->input('email')]);
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            \Log::warning('Login failed', ['email' => $request->input('email')]);
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        \Log::info('Login successful', ['user_id' => Auth::id()]);
        return response()->json(Auth::user());
    }


    public function register(Request $request)
    {
        $validated = $request->validate([
            'username'     => 'required|string|max:40|unique:users,username',
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'middle_name'  => 'nullable|string|max:100',
            'email'        => 'required|string|email|max:255|unique:users',
            'password'     => 'required|string|min:6',
            'role'         => 'required|in:admin,teacher,accountant,parent',
            'phone'        => 'nullable|string|max:9',
            'gender'       => 'nullable|in:male,female,other',
            'address'      => 'nullable|string|max:255',
            'national_id'  => 'nullable|string|max:20',
            'qualification'=> 'nullable|string|max:100',
            'subject_ids.*'=> 'integer|exists:subjects,id',
            'salutation'   => 'nullable|string|max:10',
            'tsc_number'   => 'nullable|string|max:20',
            'employer'     => 'nullable|string|max:100',
            'date_of_birth'=> 'nullable|date',
            'staff_id'     => 'nullable|string|max:50',
            'department'   => 'nullable|string|max:100',
            'occupation'   => 'nullable|string|max:100',
            'device_fingerprint' => 'nullable|string|max:255'
        ]);

        $phone = $validated['phone'] ?? null;
        if ($phone && strpos($phone, '+254') !== 0) {
            $phone = ltrim($phone, '0');
            $phone = '+254' . $phone;
        }

        $profile = null;

        DB::transaction(function () use ($validated, $phone, &$profile) {
            $user = User::create([
                'username'   => $validated['username'],
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'middle_name'=> $validated['middle_name'] ?? null,
                'email'      => $validated['email'],
                'password'   => Hash::make($validated['password']),
                'role'       => $validated['role'],
                'phone'      => $phone,
                'gender'     => $validated['gender'] ?? null,
                'address'    => $validated['address'] ?? null,
                'national_id'=> $validated['national_id'] ?? null,
                'device_fingerprint' => $validated['device_fingerprint'] ?? null
            ]);

            switch ($user->role) {
                case 'teacher':
                    $profile = Teacher::create([
                        'user_id'       => $user->id,
                        'staff_id'      => $validated['staff_id'] ?? strtoupper('TCH-' . rand(1000, 9999)),
                        'tsc_number'    => $validated['tsc_number'] ?? null,
                        'salutation'    => $validated['salutation'] ?? null,
                        'employer'      => $validated['employer'] ?? null,
                        'date_of_birth' => $validated['date_of_birth'] ?? null,
                        'qualification' => $validated['qualification'] ?? null,
                        'dept'          => $validated['department'] ?? null,
                    ]);
                    if (!empty($validated['subject_ids'])) {
                        $profile->specializedSubjects()->sync($validated['subject_ids']);
                    }
                    break;

                case 'parent':
                    $profile = Guardian::create([
                        'user_id'       => $user->id,
                        'occupation'    => $validated['occupation'] ?? null,
                        'students_health' => $validated['child_health'] ?? null,
                        'relation'      => $validated['relation'] ?? 'parent',
                    ]);
                    break;

                case 'accountant':
                    $profile = Accountant::create([
                        'user_id'     => $user->id,
                        'staff_id'    => $validated['staff_id'] ?? strtoupper('ACC-' . rand(1000, 9999)),
                        'department'  => $validated['department'] ?? 'Accounts',
                        'phone'       => $phone,
                        'qualification' => $validated['qualification'] ?? null,
                    ]);
                    break;
            }
        });

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $validated['email'],
            'profile' => $profile,
        ], 201);
    }
    


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
