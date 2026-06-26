<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = [
        'student_id',
        'offer_id',
        'supervisor_id',
        'status',
        'start_date',
        'end_date',
        'report',
        'jury_members',
        'defense_date',
        'final_grade'
    ];

    protected $casts = [
        'defense_date' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
        'final_grade' => 'float'
    ];

    public function student()
    {
        return $this->belongsTo(
            User::class,
            'student_id'
        );
    }

    public function offer()
    {
        return $this->belongsTo(
            Offer::class
        );
    }

    public function supervisor()
    {
        return $this->belongsTo(
            User::class,
            'supervisor_id'
        );
    }

    public function weeklyReports()
    {
        return $this->hasMany(WeeklyReport::class);
    }
}