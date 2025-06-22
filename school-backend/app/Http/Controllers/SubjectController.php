<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'subjects' => Subject::select('id', 'name', 'category')->orderBy('name')->get()
        ]);
    }
}

