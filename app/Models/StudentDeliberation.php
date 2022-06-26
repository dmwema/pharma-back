<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDeliberation extends Model
{
    protected $fillable = [
        'exam',
        'annual',
        'student_id',
        'deliberation_id',
        'cote'
    ];
}
