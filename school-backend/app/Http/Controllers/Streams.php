<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassStream;

class Streams extends Controller
{
    public function index()
    {
        // Eager load class, stream, and teacher with user info
        $data = ClassStream::with([
            'class',
            'stream',
            'teacher.user' // assumes teacher has related user profile
        ])->get();

        // Transform into readable structure
        $class_stream = $data->map(function ($item) {
            return [
                'class' => $item->class->name ?? null,
                'stream' => $item->stream->name ?? null,
                'teacher' => $item->teacher ? [
                    'name' => $item->teacher->salutation . ' ' . $item->teacher->user->first_name . ' ' . $item->teacher->user->last_name,
                    'email' => $item->teacher->user->email ?? null,
                ] : null,
            ];
        });

        return response()->json($class_stream);
    }
}

