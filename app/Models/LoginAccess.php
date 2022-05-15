<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoginAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'link',
        'secret',
        'is_used',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
