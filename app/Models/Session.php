<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Examen;

class Session extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title'
    ];

    public function examens()
    {
        return $this->belongsToMany(Examen::class);
    }
}
