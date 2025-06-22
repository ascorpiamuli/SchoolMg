<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guardian as GuardianModel;
use Illuminate\Support\Facades\Auth;

class Guardian extends Controller
{
    public function index()
    {
       $guardians = GuardianModel::with(['user', 'students'])->get();
        return response()->json($guardians);
    }

    public function show($id)
    {
        $guardian = GuardianModel::find($id);
        if ($guardian) {
            return response()->json($guardian);
        }
        return response()->json(['message' => 'Guardian not found'], 404);
    }

    public function store(Request $request)
    {
        $guardian = GuardianModel::create($request->all());
        return response()->json($guardian, 201);
    }

    public function update(Request $request, $id)
    {
        $guardian = GuardianModel::find($id);
        if ($guardian) {
            $guardian->update($request->all());
            return response()->json($guardian);
        }
        return response()->json(['message' => 'Guardian not found'], 404);
    }

    public function destroy($id)
    {
        $guardian = GuardianModel::find($id);
        if ($guardian) {
            $guardian->delete();
            return response()->json(['message' => 'Guardian deleted successfully']);
        }
        return response()->json(['message' => 'Guardian not found'], 404);
    }
}
