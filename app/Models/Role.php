<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable =[
        'name_role'
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
