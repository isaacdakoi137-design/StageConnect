<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'company',
        'location',
        'domain',
        'education_level',
        'salary',
        'duration',
        'contract_type',
        'work_mode',
        'required_skills',
        'description',
        'deadline'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    } 
}
