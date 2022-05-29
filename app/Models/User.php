<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Lumen\Auth\Authorizable;
use App\Models\Student;
use App\Models\Professor;
use App\Models\Role;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email', 'student_id', 'professor_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function login_access()
    {
        return $this->belongsTo(LoginAccess::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function with_datas()
    {
        if ($this->student_id !== null) {
            // get prof datas
            $student = Student::find($this->student_id);
            return ['user' => $this, 'student' => $student, 'isProf' => false, 'isStudent' => true];
        } else if ($this->professor_id !== null) {
            // get student datas
            $professor = Professor::find($this->professor_id);
            return ['user' => $this, 'professor' => $professor, 'isProf' => true, 'isStudent' => false];
        }

        return $this;
    }
}
