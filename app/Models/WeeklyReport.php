<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'week_number',
        'tasks_done',
        'difficulties',
        'observations',
        'status'
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}
