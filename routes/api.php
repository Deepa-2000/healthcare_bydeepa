<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);




Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // doctor
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/search_doctors', [DoctorController::class, 'search']);
    Route::post('/doctor_create', [DoctorController::class, 'create']);
    Route::post('/doctor_read', [DoctorController::class, 'read']);
    Route::get('/doctor_show/{id}', [DoctorController::class, 'show']);
    Route::post('/doctor_update/{id}', [DoctorController::class, 'update']);
    Route::post('/doctor_delete/{id}', [DoctorController::class, 'delete']);
});

Route::middleware(['auth:sanctum', 'role:admin,doctor'])->group(function () {

    // patient
    Route::get('/patients', [PatientController::class, 'index']);
    Route::get('/search_patients', [PatientController::class, 'search']);
    Route::post('/patient_create', [PatientController::class, 'create']);
    Route::post('/patient_read', [PatientController::class, 'read']);
    Route::get('/patient_show/{id}', [PatientController::class, 'show']);
    Route::post('/patient_update/{id}', [PatientController::class, 'update']);
    Route::post('/patient_delete/{id}', [PatientController::class, 'delete']);


});

Route::post('/diagnoses', [DiagnosisController::class, 'create'])->middleware(['auth:sanctum', 'role:doctor']);
Route::get('/search_diagnoses', [DiagnosisController::class, 'search'])->middleware(['auth:sanctum', 'role:doctor']);

Route::post('/treatments', [TreatmentController::class, 'create'])->middleware(['auth:sanctum', 'role:doctor']);
Route::get('/search_treatments', [TreatmentController::class, 'search'])->middleware(['auth:sanctum', 'role:doctor']);

Route::get('/diagnoses/{patient_id}', [DiagnosisController::class, 'show'])->middleware(['auth:sanctum', 'role:doctor,patient']);
Route::get('/treatments/{diagnosis_id}', [TreatmentController::class, 'show'])->middleware(['auth:sanctum', 'role:doctor,patient']);

Route::get('/email_treatments/{tid}', [TreatmentController::class, 'updateTreatment'])->middleware(['auth:sanctum', 'role:admin,doctor']);
