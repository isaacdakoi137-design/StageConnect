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
        'report'
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
}