<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{

    public function index()
    {
        if (!in_array(Auth::user()->role, ['admin', 'doctor'])) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => true,
            'message' => "Fetch all doctors successfully",
            'data' => Patient::with('user')->get(),
            'total_count'=> Patient::count()

        ]);
    }

    public function search(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'doctor'])) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }
        // search
        $query=Patient::with('user');
        if ($request->has('search')) {
            $search = $request->input('search');
            $patients=$query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")->orWhere('email', 'LIKE', "%$search%");
            });
        }else {
            $patients=$query;
        }

        // Filter by gender
        if ($request->has('gender')) {
            $patients=$query->where('gender', $request->input('gender'));
        }
        if ($patients->count() === 0) { 
            return response()->json([
                'status' => false,
                'message' => 'No matching results found.'
            ], 404);
        }
        $patients = $patients->get();

        return response()->json([
            'status'=>true,
            'message'=>"Fetch all patients successfully",
            'data'=> $patients
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'dob' => 'required|date_format:Y-m-d',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'required|string|max:3',
            'contact' => 'required|string|max:15',
            'address' => 'required|string|max:500',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create User with role 'patient'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->contact),
            'role' => 'patient',
        ]);
        $patient = Patient::create([
            'user_id' => $user['id'],
            'dob' => \Carbon\Carbon::createFromFormat('Y-m-d', $request->dob)->format('Y-m-d'),
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'contact' => $request->contact,
            'address' => $request->address,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Patient added successfully',
        ], 201);
    }

    // Get All Patients
    public function read()
    {
        $patients = Patient::with('user')->get();

        foreach ($patients as $patient) {
            $patient->name = $patient->user->name;
            $patient->email = $patient->user->email;
            unset($patient->user);
        }


        return response()->json([
            'status' => true,
            'message' => 'Fetch patients successfully',
            'patients' => $patients,
        ]);
    }

    public function show($id)
    {
        $patient = Patient::with('user')->find($id);

        if ($patient && $patient->user) {
            $patient->name = $patient->user->name;
            $patient->email = $patient->user->email;
            unset($patient->user);
        }

        if (!$patient) {
            return response()->json(['status' => true, 'message' => 'Patient not found'], 404);
        }

        return response()->json(['status' => true, 'message' => 'Fetch patient successfully', 'patient' => $patient]);
    }

    // Update Doctor
    public function update(Request $request, $id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['status' => true, 'message' => 'Patient not found'], 404);
        }

        $patient->update([
            'dob' => $request->dob ?? $patient->dob,
            'gender' => $request->gender ?? $patient->gender,
            'blood_group' => $request->blood_group ?? $patient->blood_group,
            'contact' => $request->contact ?? $patient->contact,
            'address' => $request->address ?? $patient->address,
        ]);

        // Update associated User details
        $patient->user->update([
            'name' => $request->name ?? $patient->user->name,
            'email' => $request->email ?? $patient->user->email,
        ]);

        return response()->json(['status' => true, 'message' => 'Patient updated successfully', 'doctor' => $patient]);
    }

    // Delete Doctor
    public function delete($id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json(['status' => true, 'message' => 'Doctor not found'], 404);
        }

        // Delete User and Doctor
        $patient->user->delete();
        $patient->delete();

        return response()->json(['status' => true, 'message' => 'Patient deleted successfully']);
    }
}
