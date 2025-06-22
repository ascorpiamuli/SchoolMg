<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student;
use App\Http\Controllers\Teacher;
use App\Http\Controllers\Guardian;
use App\Http\Controllers\Classes;
use App\Http\Controllers\Streams;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherAttendanceController;
use Illuminate\Support\Facades\Http;

Route::middleware(['web'])->group(function () {

    // 🔐 Get CSRF cookie
    Route::get('/csrf-cookie', function () {
        $token = csrf_token();
        Log::info('[CSRF] Token issued:', ['token' => $token]);
        return response()->json(['csrf' => $token]);
    });

    // 👤 Register
    Route::post('/register', [AuthController::class, 'register']);

    // 🔑 Login
    Route::post('/login', function (Request $request) {
        $credentials = $request->only('email', 'password');
        Log::info('[LOGIN] Attempt from:', ['email' => $credentials['email']]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Log::info('[LOGIN] Successful:', ['user_id' => Auth::id()]);
            return response()->json(Auth::user());
        }

        Log::warning('[LOGIN] Failed attempt:', ['email' => $credentials['email']]);
        return response()->json(['message' => 'Invalid credentials'], 401);
    });

    // 🚪 Logout
    Route::post('/logout', function (Request $request) {
        Log::info('[LOGOUT] Logging out user ID:', ['user_id' => Auth::id()]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out']);
    });

    // 🧑 Authenticated user info
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        Log::info('[USER] Authenticated user info fetched:', ['user_id' => $request->user()->id]);
        return $request->user();
    });

    // 📚 Students
    Route::get('/students', [Student::class, 'index']);
    Route::post('/students/store', [Student::class, 'store']);
    Route::get('/students/{id}', [Student::class, 'show']);
    Route::put('/students/{id}', [Student::class, 'update']);
    Route::delete('/students/{id}', [Student::class, 'destroy']);
    Route::post('/students/promote/{id}', [Student::class, 'promote']);
    Route::post('/students/bulk-upload', [Student::class, 'bulkUpload']);

    // 👨‍🏫 Teachers
    Route::get('/teachers', [Teacher::class, 'index']);

    // 👨‍👩‍👧 Guardians
    Route::get('/guardians', [Guardian::class, 'index']);

    // 📖 Subjects
    Route::get('/subjects', [SubjectController::class, 'index']);

    // 🏫 Classes and Streams
    Route::get('/classes', [Classes::class, 'index']);
    Route::get('/streams', [Streams::class, 'index']);

    // 🕒 Student Attendance (Protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/attendance', [AttendanceController::class, 'store']);
        Route::get('/attendance', [AttendanceController::class, 'index']);
    });

    // 🧑‍🏫 Teacher Attendance (Protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/teacher-attendance', [TeacherAttendanceController::class, 'store']);
        Route::get('/teacher-attendance', [TeacherAttendanceController::class, 'index']);
    });

    // 🌐 IP and Geo APIs
    Route::get('/external/ip', function () {
        $ip = file_get_contents('https://api.ipify.org?format=json');
        Log::info('[IP LOOKUP] External IP fetched:', ['ip' => $ip]);
        return response($ip);
    });

    Route::get('/external/ipinfo', function () {
        $response = Http::get('https://ipinfo.io/json?token=f092eba80827a2');

        if ($response->successful()) {
            Log::info('[IPINFO] Data fetched:', $response->json());
            return response()->json($response->json());
        }

        Log::error('[IPINFO] Failed:', ['status' => $response->status(), 'body' => $response->body()]);
        return response()->json([
            'error' => 'Failed to fetch IP info',
            'details' => $response->body(),
        ], 500);
    });

    // 👤 Avatar Proxy
    Route::get('/proxy-avatar', function (Request $request) {
        $name = urlencode($request->query('name', 'Student'));
        $url = "https://ui-avatars.com/api/?name={$name}&background=0D8ABC&color=fff";
        Log::info('[AVATAR] Proxy fetch for name:', ['name' => $name]);
        $response = Http::get($url);
        return response($response->body(), 200)->header('Content-Type', 'image/png');
    });
});
