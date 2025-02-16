<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = ['diagnosis_id', 'doctor_id', 'treatment_plan', 'medications', 'follow_up_instructions'];

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

}
