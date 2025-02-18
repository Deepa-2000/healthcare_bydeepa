<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DiagnosisController extends Controller
{
    public function show($patient_id)
    {
        if (!in_array(Auth::user()->role, ['doctor', 'patient'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $diagnoses=Diagnosis::where('patient_id', $patient_id)->get();
        return response()->json([
            'status'=> true,
            'message'=> 'Fetch patient dignoses successfully',
            'data'=>$diagnoses
        ]);
    }


    public function search(Request $request)
    {
        if (!in_array(Auth::user()->role, ['doctor', 'patient'])) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $query = Diagnosis::with('patient.user');

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->input('patient_id'));
        }

        // Search by Disease Name or Description
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('diagnosis', 'LIKE', "%$search%")
                ->orWhere('symptoms', 'LIKE', "%$search%");
        }

        // Paginate results
        $diagnoses = $query->paginate(10);

        // Check if no results
        if (empty($diagnoses)) {
            return response()->json([
                'status' => false,
                'message' => 'No diagnoses found.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Fetch all diagnosis successfully",
            'data' => $diagnoses
        ]);


        // return response()->json(Diagnosis::where('patient_id', $patient_id)->get());
    }


    public function create(Request $request)
    {
        if (Auth::user()->role !== 'doctor') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'required|string',
            'symptoms' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }


        $diagnosis = Diagnosis::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id ?? Auth::user()->id,
            'diagnosis' => $request->diagnosis,
            'symptoms' => $request->symptoms,
        ]);

        return response()->json(
            [
                'status' => true,
                'message' => "Diagnosis added successfully",
                'data' => $diagnosis
            ],
            201
        );
    }
}
