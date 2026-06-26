<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'scheduled_at',
        'status',
        'video_room_id',
        'report_summary'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
