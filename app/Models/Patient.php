<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'dob', 'gender', 'blood_group', 'contact', 'address'];

    protected $casts = [
        'dob' => 'date:d/m/Y',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }
}
