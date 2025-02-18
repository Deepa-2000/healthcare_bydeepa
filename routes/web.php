<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/admin_panel', [AdminController::class, 'index']);
Route::post('/logout', function () {Auth::logout(); return redirect('/login');})->name('logout');

Route::get('/doctor_create', function () {
    return view('admin.doctor.create');
});
Route::get('/doctor_view/{id}', function () {
    return view('admin.doctor.view');
});
Route::get('/doctor_edit/{id}', function () {
    return view('admin.doctor.edit');
});
Route::get('/doctor_index', function () {
    return view('admin.doctor.index');
});

Route::get('/patient_create', function () {
    return view('admin.patient.create');
});
Route::get('/patient_view/{id}', function () {
    return view('admin.patient.view');
});
Route::get('/patient_edit/{id}', function () {
    return view('admin.patient.edit');
});
Route::get('/patient_index', function () {
    return view('admin.patient.index');
});

// Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
