<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Promotion;
use App\Models\Examen;
use App\Models\AnnualWork;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'gender',
        'avatar',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class)->withPivot(['year_start', 'year_off']);
    }

    public function examens()
    {
        return $this->belongsToMany(Examen::class)->withPivot('cote');
    }

    public function annual_works()
    {
        return $this->belongsToMany(AnnualWork::class)->withPivot('cote');
    }

    public static function with_cotes($work_id)
    {
        $students = self::all();
        $work_students = StudentWork::where('work_id', $work_id)->get();
        $return = [];
        $i = 0;

        foreach ($students as $student) {
            $cote = null;
            $student = Student::find($student->id);
            foreach ($work_students as $w_s) {
                if ($student->id === $w_s->student_id) {
                    $cote = StudentWork::where('work_id', $work_id)->where('student_id', $student->id)->first()->id;
                }
            }
            $return[] = [
                "id" => ++$i,
                "key" => $student->id,
                "names" => $student->lastname . " " . $student->middlename . " " . $student->firstname,
                "firstname" => $student->firstname,
                "lastname" => $student->lastname,
                "middlename" => $student->middlename,
                "gender" => $student->gender,
                "avatar" => $student->avatar,
                "cote" => $cote,
            ];
        }

        return $return;
    }
}
