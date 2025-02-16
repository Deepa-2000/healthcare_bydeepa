<?php

namespace App\Http\Controllers;

use App\Mail\TreatmentUpdatedMail;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TreatmentController extends Controller
{
    public function show($diagnosis_id)
    {
        if (!in_array(Auth::user()->role, ['doctor', 'patient'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $treatment = Treatment::where('diagnosis_id', $diagnosis_id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Fetch patient treaments successfully',
            'data' => $treatment
        ]);
    }

    public function search(Request $request)
    {
        if (!in_array(Auth::user()->role, ['doctor', 'patient'])) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }
        // dd($request->input('diagnosis_id'));
        $query = Treatment::with('diagnosis');

        // if ($request->has('diagnosis_id')) {
        $query->where('diagnosis_id', $request->input('diagnosis_id'));
        // }

        // Search by Disease Name or Description
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('treatment_plan', 'LIKE', "%$search%")
                ->orWhere('medications', 'LIKE', "%$search%")
                ->orWhere('follow_up_instructions', 'LIKE', "%$search%");
        }

        // Paginate results
        $treatments = $query->get();

        // Check if no results
        if (empty($treatments)) {
            return response()->json([
                'status' => false,
                'message' => 'No diagnoses found.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Fetch all treaments successfully",
            'data' => $treatments
        ]);


        // return response()->json(Diagnosis::where('patient_id', $patient_id)->get());
    }


    public function create(Request $request)
    {
        if (Auth::user()->role !== 'doctor') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'diagnosis_id' => 'required|exists:diagnoses,id',
            'treatment_plan' => 'required|string',
            'medications' => 'required|string',
            'follow_up_instructions' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $treatment = Treatment::create([
            'diagnosis_id' => $request->diagnosis_id,
            'doctor_id' => Auth::user()->id,
            'treatment_plan' => $request->treatment_plan,
            'medications' => $request->medications,
            'follow_up_instructions' => $request->follow_up_instructions,
        ]);
        return response()->json(
            [
                'status' => true,
                'message' => "Treatment added successfully",
                'data' => $treatment
            ],
            201
        );
    }

    public function updateTreatment(Request $request, $tid)
    {
        $treatment = Treatment::findOrFail($tid);
        // $treatment->update($request->only(['treatment_name', 'notes']));
        // $msg=array(
        //     'treatment_name'=> $request->treatment_name,
        //     'notes'=>$request->notes
        // );
        $msg="dummy mail";
        $sub="testing";
        $patientEmail = $treatment->diagnosis->patient->user->email;
        try {
            Mail::to($patientEmail)->send(new TreatmentUpdatedMail($msg,$sub));
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json([
            'status' => true,
            'message' => 'Treatment updated and email notification sent!',
        ]);
    }
}
