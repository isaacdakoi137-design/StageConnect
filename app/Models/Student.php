<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'birth_date',
        'city',
        'school',
        'level',
        'bio',
        'skills',
        'projects',
        'experiences',
        'certifications',
        'cv',
        'photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
