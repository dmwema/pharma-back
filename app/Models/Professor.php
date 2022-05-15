<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\user;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Professor extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'gender',
        'avatar',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function courses(){
        return $this->belongsToMany(Course::class);
    }
}
