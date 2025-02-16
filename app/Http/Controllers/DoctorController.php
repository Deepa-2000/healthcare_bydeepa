<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{

    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => true,
            'message' => "Fetch all doctors successfully",
            'data' => Doctor::with('user')->get()
        ]);
    }
    public function search(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        // Start query with Doctor model and its relationship with User
        $query = Doctor::with('user');
    
        // Search by name or email
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });
        }
    
        // Filter by specialization
        if ($request->has('specialization')) {
            $query->where('specialization', $request->input('specialization'));
        }
    
        // Execute the query
        $doctors = $query->get(); // Ensure data is retrieved
    
        // Check if results exist
        if ($doctors->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No matching results found.'
            ], 404);
        }
    
        return response()->json([
            'status' => true,
            'message' => "Fetch all doctors successfully",
            'data' => $doctors
        ]);
    }
    


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'specialization' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create User with role 'doctor'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->contact),
            'role' => 'doctor',
        ]);

        $doctor = Doctor::create([
            'user_id' => $user['id'],
            'specialization' => $request->specialization,
            'contact' => $request->contact,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Doctor added successfully',
        ], 201);
    }

    // Get All Doctors
    public function read()
    {
        $doctors = Doctor::with('user')->get();

        foreach ($doctors as $doctor) {
            $doctor->name = $doctor->user->name;
            $doctor->email = $doctor->user->email;
            unset($doctor->user);
        }


        return response()->json([
            'status' => true,
            'doctors' => $doctors,
        ]);
    }

    public function show($id)
    {
        $doctor = Doctor::with('user')->find($id);

        if ($doctor && $doctor->user) {
            $doctor->name = $doctor->user->name;
            $doctor->email = $doctor->user->email;
            unset($doctor->user);
        }

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json(['doctor' => $doctor]);
    }

    // Update Doctor
    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $doctor->update([
            'specialization' => $request->specialization ?? $doctor->specialization,
            'contact' => $request->contact ?? $doctor->contact,
        ]);

        // Update associated User details
        $doctor->user->update([
            'name' => $request->name ?? $doctor->user->name,
            'email' => $request->email ?? $doctor->user->email,
        ]);

        return response()->json(['message' => 'Doctor updated successfully', 'doctor' => $doctor]);
    }

    // Delete Doctor
    public function delete($id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        // Delete User and Doctor
        $doctor->user->delete();
        $doctor->delete();

        return response()->json(['message' => 'Doctor deleted successfully']);
    }
}
